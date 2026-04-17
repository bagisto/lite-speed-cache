<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Litespeed\LSCache\LSCache;
use Litespeed\LSCache\LSCacheMiddleware as BaseLSCacheMiddleware;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Product\Repositories\ProductRepository;

class LSCacheHeaders extends BaseLSCacheMiddleware
{
    /**
     * Routes eligible for caching.
     *
     * @var array
     */
    protected $cacheRoutes = [
        'shop.home.index',
        'shop.cms.page',
        'shop.product_or_category.index',
        'shop.home.contact_us',
        'shop.search.index',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'customer')
    {
        $routeName = $request->route()?->getName();
        $tags = $this->getRouteTags($request, $routeName, $request->getPathInfo());

        $response = $next($request);
        
        if (
            (bool) config('responsecache.enabled')
            || ! (bool) core()->getConfigData('lsc.configuration.cache_application.active')
        ) {
            return $response;
        }

        $dynamicCacheResponse = $this->handleDynamicCache($request, $response, $tags);

        if ($dynamicCacheResponse !== null) {
            return $dynamicCacheResponse;
        }

        if ($this->isShopStateRoute($request, $routeName)) {
            if ($this->shouldInvalidateHomeCache($routeName)) {
                LSCache::purgeTags(['home', 'home-header']);
            }

            return $this->setNoCacheHeaders($response);
        }

        if (! in_array($routeName, $this->cacheRoutes, true)) {
            return $this->setNoCacheHeaders($response);
        }

        // Invalidate home cache for certain actions.
        if ($this->shouldInvalidateHomeCache($routeName)) {
            LSCache::purgeTags(['home', 'home-header']);

            return $response;
        }

        $lsCacheTTL = $this->getCacheTTL();
        $lscacheControl = $this->getCacheControlHeader($lsCacheTTL);

        // Set no-cache headers if no tags or ttl is 0.
        if ($this->shouldSetNoCache($tags, $lsCacheTTL)) {
            return $this->setNoCacheHeaders($response);
        }

        // Set LiteSpeed Cache headers.
        if (
            ! (
                in_array($request->getMethod(), ['GET', 'HEAD'])
                && $response->getContent()
                && $response->getStatusCode() === 200
            )
        ) {
            return $this->setNoCacheHeaders($response);
        }

        $this->setCacheHeaders($response, $tags, $lscacheControl);

        return $response;
    }

    /**
     * Handle dynamic cache for product/category/home.
     */
    private function handleDynamicCache($request, $response, array $tags)
    {
        $route = $request->route();
        $routeName = $route?->getName();
        $method = $request->getMethod();

        $isProductOrCategory = $routeName === 'shop.product_or_category.index' && in_array($method, ['GET', 'HEAD']);
        $isHomePage = $routeName === 'shop.home.index' && in_array($method, ['GET', 'HEAD']);

        if (! ($isProductOrCategory || $isHomePage)) {
            return null;
        }

        $slug = $isProductOrCategory ? urldecode(trim($request->getPathInfo(), '/')) : null;
        $cacheKey = $isProductOrCategory ? 'product_or_category_'.$slug : ($isHomePage ? 'home_page' : null);
        $cachedData = $cacheKey ? cache()->get($cacheKey) : null;

        $content = $response->getContent();
        $controllerData = json_decode($content, true);

        if (
            $cachedData
            && $controllerData
            && $cachedData == $controllerData
        ) {
            $cachedResponse = response()->json($cachedData);
            $this->setCacheHeaders($cachedResponse, $tags, $this->getCacheControlHeader($this->getCacheTTL()));

            return $cachedResponse;
        } elseif ($controllerData) {
            cache()->put($cacheKey, $controllerData, now()->addMinutes(10));

            return $response;
        }

        return null;
    }

    /**
     * Get tags for the current route.
     */
    private function getRouteTags($request, $routeName, $routePathInfo): array
    {
        $slug = urldecode(trim($routePathInfo, '/'));
        $lastSegment = last(explode('/', $routePathInfo));

        return match ($routeName) {
            'shop.home.index'                => ['home'],
            'shop.cms.page'                  => ["page_$lastSegment"],
            'shop.product_or_category.index' => $this->getProductOrCategoryTags($slug),
            'shop.home.contact_us'           => ['contact'],
            'shop.search.index'              => array_merge(['search'], $this->getCategoryTagsFromRequest($request)),
            'shop.compare.index'             => ['compare'],
            default                          => [],
        };
    }

    /**
     * Get category tags from request parameters.
     */
    private function getCategoryTagsFromRequest($request): array
    {
        $categoryId = (int) $request->query('category_id');

        if (! $categoryId) {
            return [];
        }

        $tags = ['category_id_'.$categoryId];
        $category = app(CategoryRepository::class)->find($categoryId);

        if ($category?->slug) {
            $tags[] = 'category_'.$category->slug;
        }

        return $tags;
    }

    /**
     * Should invalidate home cache for certain actions.
     */
    private function shouldInvalidateHomeCache($routeName): bool
    {
        return in_array($routeName, [
            'shop.api.checkout.cart.store',
            'shop.api.checkout.cart.destroy',
        ], true);
    }

    /**
     * Cart endpoints are session-specific and must never be LiteSpeed cached.
     */
    private function isShopStateRoute($request, ?string $routeName): bool
    {
        return str_starts_with((string) $routeName, 'shop.api.checkout.cart.')
            || str_starts_with((string) $routeName, 'shop.api.compare.')
            || str_starts_with((string) $routeName, 'shop.api.customers.account.wishlist.')
            || $routeName === 'shop.compare.index'
            || $routeName === 'shop.customers.account.wishlist.index'
            || $request->is('api/checkout/cart')
            || $request->is('api/checkout/cart/*')
            || $request->is('api/compare-items')
            || $request->is('api/compare-items/*')
            || $request->is('api/customer/wishlist')
            || $request->is('api/customer/wishlist/*')
            || $request->is('checkout/cart')
            || $request->is('checkout/cart/*')
            || $request->is('compare')
            || $request->is('customer/account/wishlist');
    }

    /**
     * Get cache TTL from config/env.
     */
    private function getCacheTTL(): int
    {
        return (int) (env('LSCACHE_DEFAULT_TTL', core()->getConfigData('lsc.configuration.cache_application.default_ttl')));
    }

    /**
     * Get cache control header value.
     */
    private function getCacheControlHeader($ttl): string
    {
        $cacheability = env('LSCACHE_DEFAULT_CACHEABILITY', 'public');

        return "$cacheability, max-age=$ttl";
    }

    /**
     * Should set no-cache headers based on tags/ttl.
     */
    private function shouldSetNoCache($tags, $ttl): bool
    {
        return (is_array($tags) && count(array_filter($tags)) === 0) || (is_numeric($ttl) && (int) $ttl === 0);
    }

    /**
     * Get tags for product or category based on the request.
     */
    private function getProductOrCategoryTags(string $slug): array
    {
        if ($category = app(CategoryRepository::class)->findBySlug($slug)) {
            return [
                'category_'.$slug,
                'category_id_'.$category->id,
            ];
        }

        if (core()->getConfigData('catalog.products.search.engine') == 'elastic') {
            $searchEngine = core()->getConfigData('catalog.products.search.storefront_mode');
        }

        $product = app(ProductRepository::class)
            ->setSearchEngine($searchEngine ?? 'database')
            ->findBySlug($slug);

        if ($product) {
            if (
                ! $product->url_key
                || ! $product->visible_individually
                || ! $product->status
            ) {
                abort(404);
            }

            return [
                'product_'.$slug,
                'product_id_'.$product->id,
            ];
        }

        return ['slug_'.$slug]; // fallback
    }

    /**
     * Set no-cache headers for the response.
     */
    private function setNoCacheHeaders($response)
    {
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');

        $response->headers->set('X-LiteSpeed-Cache-Control', 'no-cache');
        $response->headers->remove('X-LiteSpeed-Tag');

        return $response;
    }

    /**
     * Apply cache headers and make the tag list visible to the browser.
     */
    private function setCacheHeaders($response, array $tags, string $cacheControl): void
    {
        $tagList = implode(',', $this->normalizeTags(array_merge(
            $this->getExistingTags($response->headers->get('X-LiteSpeed-Tag')),
            $tags
        )));

        $response->headers->set('Cache-Control', $cacheControl);
        $response->headers->set('X-LiteSpeed-Cache-Control', $cacheControl);
        $response->headers->set('X-LiteSpeed-Tag', $tagList);
    }

    /**
     * Parse existing tag header.
     */
    private function getExistingTags(?string $headerValue): array
    {
        if (! $headerValue) {
            return [];
        }

        return array_map('trim', explode(',', $headerValue));
    }

    /**
     * Normalize tag values.
     */
    private function normalizeTags(array $tags): array
    {
        $tags = array_map(function ($tag) {
            return preg_replace('/[^A-Za-z0-9_\-]/', '_', (string) $tag);
        }, array_filter($tags));

        return array_values(array_unique(array_filter($tags)));
    }
}
