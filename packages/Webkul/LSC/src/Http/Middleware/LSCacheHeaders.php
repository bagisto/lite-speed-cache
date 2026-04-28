<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Litespeed\LSCache\LSCacheMiddleware as BaseLSCacheMiddleware;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\CMS\Repositories\PageRepository;
use Webkul\LSC\Support\DebuggableLSCache as LSCache;
use Webkul\LSC\Support\LiteSpeedDebug;
use Webkul\Marketing\Repositories\URLRewriteRepository;
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
        'shop.api.categories.index',
        'shop.api.categories.attributes',
        'shop.api.categories.tree',
        'shop.api.products.index',
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

        $response = $next($request);

        if (
            (bool) config('responsecache.enabled')
            || ! (bool) core()->getConfigData('lsc.configuration.cache_application.active')
        ) {
            return $this->setNoCacheHeaders($response);
        }

        $dynamicCacheResponse = $this->handleDynamicCache($request, $response);

        if ($dynamicCacheResponse !== null) {
            return LiteSpeedDebug::attachToResponse($dynamicCacheResponse);
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

        $tags = $this->getRouteTags($routeName, $request);

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

        // For browsers: prevent caching of dynamic HTML (avoids stale content)
        // For LiteSpeed: enable server-side caching
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
        $response->headers->set('X-LiteSpeed-Cache-Control', $lscacheControl);
        $response->headers->set('X-LiteSpeed-Tag', implode(',', $tags));

        return LiteSpeedDebug::attachToResponse($response, $tags, $lscacheControl);
    }

    /**
     * Handle dynamic cache for product/category/home.
     */
    private function handleDynamicCache($request, $response)
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
            return response()->json($cachedData);
        } elseif ($controllerData) {
            cache()->put($cacheKey, $controllerData, now()->addMinutes(10));

            return $response;
        }

        return null;
    }

    /**
     * Get tags for the current route.
     */
    private function getRouteTags($routeName, $request): array
    {
        $routePathInfo = $request->getPathInfo();
        $slug = urldecode(trim($routePathInfo, '/'));
        $lastSegment = last(explode('/', $routePathInfo));

        return match ($routeName) {
            'shop.home.index'                => ['home'],
            'shop.cms.page'                  => $this->getPageTags($lastSegment),
            'shop.product_or_category.index' => $this->getProductOrCategoryTags($slug),
            'shop.home.contact_us'           => ['contact'],
            'shop.search.index'              => ['search'],
            'shop.compare.index'             => ['compare'],
            'shop.api.categories.index'      => ['home-categories'],
            'shop.api.categories.attributes' => $this->getCategoryFilterTags($request),
            'shop.api.categories.tree'       => ['home-header'],
            'shop.api.products.index'        => $this->getProductListingTags($request),
            default                          => [],
        };
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
            return ['category_'.$category->id];
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

            return ['product_'.$product->id];
        }

        $segments = array_values(array_filter(explode('/', $slug)));
        $lastSegment = end($segments) ?: $slug;

        if ($lastSegment && $category = app(CategoryRepository::class)->findBySlug($lastSegment)) {
            return ['category_'.$category->id];
        }

        $urlRewriteRepository = app(URLRewriteRepository::class);

        $categoryURLRewrite = $urlRewriteRepository->findOneWhere([
            'entity_type'  => 'category',
            'request_path' => $slug,
            'locale'       => app()->getLocale(),
        ]);

        if (
            $categoryURLRewrite
            && $category = app(CategoryRepository::class)->find($categoryURLRewrite->entity_id)
        ) {
            return ['category_'.$category->id];
        }

        $productURLRewrite = $urlRewriteRepository->findOneWhere([
            'entity_type'  => 'product',
            'request_path' => $slug,
        ]);

        if (
            $productURLRewrite
            && $product = app(ProductRepository::class)
                ->setSearchEngine($searchEngine ?? 'database')
                ->find($productURLRewrite->entity_id)
        ) {
            if (
                ! $product->url_key
                || ! $product->visible_individually
                || ! $product->status
            ) {
                abort(404);
            }

            return ['product_'.$product->id];
        }

        return []; // fallback
    }

    /**
     * Get tags for CMS page routes.
     */
    private function getPageTags(string $slug): array
    {
        if ($page = app(PageRepository::class)->findOneWhere(['url_key' => $slug])) {
            return ['page_'.$page->id];
        }

        return [];
    }

    /**
     * Get tags for product listing API routes.
     */
    private function getProductListingTags($request): array
    {
        $categoryId = (int) $request->query('category_id');

        if ($categoryId > 0) {
            return ['category-products_'.$categoryId];
        }

        return [];
    }

    /**
     * Get tags for category filter API routes.
     */
    private function getCategoryFilterTags($request): array
    {
        $categoryId = (int) $request->query('category_id');

        if ($categoryId > 0) {
            return ['category-filters_'.$categoryId];
        }

        return [];
    }

    /**
     * Set no-cache headers for the response.
     */
    private function setNoCacheHeaders($response)
    {
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');

        $response->headers->set('X-LiteSpeed-Cache-Control', 'no-cache');

        return LiteSpeedDebug::attachToResponse($response, [], 'no-cache');
    }
}
