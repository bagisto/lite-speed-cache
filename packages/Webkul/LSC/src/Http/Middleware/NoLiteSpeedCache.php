<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Product\Repositories\ProductRepository;

class NoLiteSpeedCache
{
    /**
     * Create a new middleware instance.
     */
    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected ProductRepository $productRepository,
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $routeName = $request->route()?->getName();

        if ($this->isLsCacheDisabled()) {
            return $response;
        }

        if ($this->isNeverCachedRoute($request, $routeName)) {
            $this->setNoCacheHeaders($response);

            return $response;
        }

        if ($this->hasNoCacheDirective($response)) {
            $this->setNoCacheHeaders($response);

            return $response;
        }

        if (! $this->isCacheableResponse($request, $response)) {
            $this->setNoCacheHeaders($response);

            return $response;
        }

        $tags = array_merge(
            $this->getExistingTags($response->headers->get('X-LiteSpeed-Tag')),
            $this->getRouteTags($request, $routeName)
        );

        $tags = $this->normalizeTags($tags);

        if (empty($tags)) {
            $this->setNoCacheHeaders($response);

            return $response;
        }

        $this->setCacheHeaders($response, $tags, $this->getCacheControlHeader($routeName));

        if (
            $routeName
            && str_starts_with($routeName, 'shop.api.home.login_')
            && ! $response->headers->has('X-LiteSpeed-Vary')
        ) {
            $response->headers->set('X-LiteSpeed-Vary', 'cookie=laravel_session');
        }

        return $response;
    }

    /**
     * Determine whether LSCache is disabled.
     */
    private function isLsCacheDisabled(): bool
    {
        return (bool) config('responsecache.enabled')
            || ! (bool) core()->getConfigData('lsc.configuration.cache_application.active');
    }

    /**
     * Determine whether the response can be cached by LiteSpeed.
     */
    private function isCacheableResponse(Request $request, $response): bool
    {
        return in_array($request->getMethod(), ['GET', 'HEAD'], true)
            && $response->getStatusCode() === 200;
    }

    /**
     * Resolve cache-control for route type.
     */
    private function getCacheControlHeader(?string $routeName): string
    {
        if ($routeName && str_starts_with($routeName, 'shop.api.home.login_')) {
            return 'private, max-age=300';
        }

        $ttl = (int) (env('LSCACHE_DEFAULT_TTL', core()->getConfigData('lsc.configuration.cache_application.default_ttl')) ?: 3600);

        return 'public, max-age='.$ttl;
    }

    /**
     * Resolve tags for cacheable requests.
     */
    private function getRouteTags(Request $request, ?string $routeName): array
    {
        $routePathInfo = $request->getPathInfo();
        $slug = urldecode(trim($routePathInfo, '/'));
        $lastSegment = last(explode('/', $routePathInfo));

        return match ($routeName) {
            'shop.home.index'                      => ['home'],
            'shop.cms.page'                        => ["page_$lastSegment"],
            'shop.product_or_category.index'       => $this->getProductOrCategoryTags($slug),
            'shop.home.contact_us'                 => ['contact'],
            'shop.search.index'                    => array_merge(['search'], $this->getCategoryTagsFromRequest($request)),
            'shop.api.core.countries'              => ['api', 'core', 'countries'],
            'shop.api.core.states'                 => ['api', 'core', 'states'],
            'shop.api.categories.index'            => array_merge(['api', 'categories'], $this->getCategoryTagsFromRequest($request)),
            'shop.api.categories.tree'             => ['api', 'categories', 'categories-tree'],
            'shop.api.categories.attributes'       => ['api', 'categories', 'category-attributes'],
            'shop.api.categories.attribute_options' => array_merge(
                ['api', 'categories', 'category-attribute-options'],
                $request->route('attribute_id') ? ['attribute_'.$request->route('attribute_id')] : []
            ),
            'shop.api.categories.max_price'        => array_merge(
                ['api', 'categories', 'categories-max-price'],
                $this->getCategoryTagsById((int) ($request->route('id') ?: $request->query('id')))
            ),
            'shop.api.products.index'              => $this->getProductListingTags($request),
            'shop.api.products.related.index'      => array_merge(
                ['api', 'products', 'product-related'],
                $this->getProductTagsById((int) $request->route('id'))
            ),
            'shop.api.products.up-sell.index'      => array_merge(
                ['api', 'products', 'product-up-sell'],
                $this->getProductTagsById((int) $request->route('id'))
            ),
            'shop.api.products.reviews.index'      => array_merge(
                ['api', 'products', 'product-reviews'],
                $this->getProductTagsById((int) $request->route('id'))
            ),
            'shop.api.home.login_desktop_dropdown' => ['login', 'login-desktop-dropdown'],
            'shop.api.home.login_mobile_dropdown'  => ['login', 'login-mobile-dropdown'],
            'shop.api.home.login_mobile_drawer'    => ['login', 'login-mobile-drawer'],
            default                                => $this->getFallbackTags($request, $routeName),
        };
    }

    /**
     * Resolve tags for product and category storefront URLs.
     */
    private function getProductOrCategoryTags(string $slug): array
    {
        if ($slug === '') {
            return ['home'];
        }

        if ($category = $this->categoryRepository->findBySlug($slug)) {
            return $this->normalizeTags([
                'category_'.$slug,
                'category_id_'.$category->id,
            ]);
        }

        if (core()->getConfigData('catalog.products.search.engine') == 'elastic') {
            $searchEngine = core()->getConfigData('catalog.products.search.storefront_mode');
        }

        $product = $this->productRepository
            ->setSearchEngine($searchEngine ?? 'database')
            ->findBySlug($slug);

        if ($product) {
            return $this->normalizeTags([
                'product_'.$slug,
                'product_id_'.$product->id,
            ]);
        }

        return $this->normalizeTags([
            'slug_'.$slug,
        ]);
    }

    /**
     * Resolve tags for product listing API endpoints.
     */
    private function getProductListingTags(Request $request): array
    {
        $tags = ['api', 'products', 'product-list'];

        if ($request->boolean('new')) {
            $tags[] = 'products-new';
        }

        if ($request->boolean('featured')) {
            $tags[] = 'products-featured';
        }

        return array_merge($tags, $this->getCategoryTagsFromRequest($request));
    }

    /**
     * Resolve category tags from common request inputs.
     */
    private function getCategoryTagsFromRequest(Request $request): array
    {
        $categoryId = (int) ($request->query('category_id') ?: $request->route('id'));

        if (! $categoryId) {
            return [];
        }

        return $this->getCategoryTagsById($categoryId);
    }

    /**
     * Resolve product tags by ID.
     */
    private function getProductTagsById(int $productId): array
    {
        if (! $productId) {
            return [];
        }

        $tags = ['product_id_'.$productId];
        $product = $this->productRepository->find($productId);

        if ($product?->url_key) {
            $tags[] = 'product_'.$product->url_key;
        }

        return $tags;
    }

    /**
     * Resolve category tags by ID.
     */
    private function getCategoryTagsById(int $categoryId): array
    {
        if (! $categoryId) {
            return [];
        }

        $tags = ['category_id_'.$categoryId];
        $category = $this->categoryRepository->find($categoryId);

        if ($category?->slug) {
            $tags[] = 'category_'.$category->slug;
        }

        return $tags;
    }

    /**
     * Resolve fallback tags for public cacheable routes not explicitly mapped.
     */
    private function getFallbackTags(Request $request, ?string $routeName): array
    {
        if (! $this->isPublicCacheCandidate($request, $routeName)) {
            return [];
        }

        $path = trim($request->path(), '/');

        return $this->normalizeTags(array_filter([
            'public',
            $routeName ? 'route_'.$routeName : null,
            $path !== '' ? 'path_'.str_replace('/', '_', $path) : 'home',
        ]));
    }

    /**
     * Determine whether the current request is a public cache candidate.
     */
    private function isPublicCacheCandidate(Request $request, ?string $routeName): bool
    {
        return ! str_starts_with((string) $routeName, 'admin.')
            && ! str_starts_with((string) $routeName, 'shop.customers.')
            && ! str_starts_with((string) $routeName, 'shop.api.customers.')
            && ! str_starts_with((string) $routeName, 'shop.checkout.onepage.')
            && ! $request->is('admin')
            && ! $request->is('admin/*')
            && ! $request->is('customer')
            && ! $request->is('customer/*')
            && ! $request->is('api/customer')
            && ! $request->is('api/customer/*')
            && ! $request->is('api/checkout/onepage')
            && ! $request->is('api/checkout/onepage/*');
    }

    /**
     * Parse tags already attached upstream.
     */
    private function getExistingTags(?string $headerValue): array
    {
        if (! $headerValue) {
            return [];
        }

        return array_map('trim', explode(',', $headerValue));
    }

    /**
     * Normalize tags to LiteSpeed-safe values.
     */
    private function normalizeTags(array $tags): array
    {
        $tags = array_map(function ($tag) {
            return preg_replace('/[^A-Za-z0-9_\-]/', '_', (string) $tag);
        }, array_filter($tags));

        return array_values(array_unique(array_filter($tags)));
    }

    /**
     * Detect whether no-cache is already required.
     */
    private function hasNoCacheDirective($response): bool
    {
        $cacheControl = strtolower((string) $response->headers->get('Cache-Control'));
        $lsCacheControl = strtolower((string) $response->headers->get('X-LiteSpeed-Cache-Control'));

        return str_contains($cacheControl, 'no-cache')
            || str_contains($cacheControl, 'no-store')
            || str_contains($lsCacheControl, 'no-cache')
            || str_contains($lsCacheControl, 'no-store');
    }

    /**
     * Set no-cache headers for the response.
     */
    private function setNoCacheHeaders($response): void
    {
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('X-LiteSpeed-Cache-Control', 'no-cache');
        $response->headers->remove('X-LiteSpeed-Tag');
    }

    /**
     * Set cache headers and LiteSpeed tags.
     */
    private function setCacheHeaders($response, array $tags, string $cacheControl): void
    {
        $tagList = implode(',', $this->normalizeTags($tags));
        $currentCacheControl = strtolower((string) $response->headers->get('Cache-Control'));
        $currentLsCacheControl = strtolower((string) $response->headers->get('X-LiteSpeed-Cache-Control'));

        if ($currentCacheControl === '' || str_contains($currentCacheControl, 'no-cache') || str_contains($currentCacheControl, 'no-store')) {
            $response->headers->set('Cache-Control', $cacheControl);
        }

        if ($currentLsCacheControl === '' || str_contains($currentLsCacheControl, 'no-cache') || str_contains($currentLsCacheControl, 'no-store')) {
            $response->headers->set('X-LiteSpeed-Cache-Control', $cacheControl);
        }

        $response->headers->set('X-LiteSpeed-Tag', $tagList);
    }

    /**
     * Customer, cart, checkout, compare and admin routes must never be LiteSpeed cached.
     */
    private function isNeverCachedRoute(Request $request, ?string $routeName): bool
    {
        return str_starts_with((string) $routeName, 'shop.api.checkout.cart.')
            || str_starts_with((string) $routeName, 'shop.api.checkout.onepage.')
            || str_starts_with((string) $routeName, 'shop.api.compare.')
            || str_starts_with((string) $routeName, 'shop.api.customers.account.wishlist.')
            || str_starts_with((string) $routeName, 'shop.api.customers.')
            || str_starts_with((string) $routeName, 'shop.customers.')
            || str_starts_with((string) $routeName, 'shop.checkout.onepage.')
            || str_starts_with((string) $routeName, 'admin.')
            || $routeName === 'shop.checkout.cart.index'
            || $routeName === 'shop.compare.index'
            || $routeName === 'shop.customers.account.wishlist.index'
            || $request->is('admin')
            || $request->is('admin/*')
            || $request->is('api/checkout/cart')
            || $request->is('api/checkout/cart/*')
            || $request->is('api/checkout/onepage')
            || $request->is('api/checkout/onepage/*')
            || $request->is('api/compare-items')
            || $request->is('api/compare-items/*')
            || $request->is('api/customer/wishlist')
            || $request->is('api/customer/wishlist/*')
            || $request->is('api/customer')
            || $request->is('api/customer/*')
            || $request->is('checkout/cart')
            || $request->is('checkout/cart/*')
            || $request->is('checkout/onepage')
            || $request->is('checkout/onepage/*')
            || $request->is('compare')
            || $request->is('customer')
            || $request->is('customer/*')
            || $request->is('customer/account/wishlist');
    }

}
