<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Litespeed\LSCache\LSCacheMiddleware as BaseLSCacheMiddleware;
use LSCache;
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
        'shop.api.checkout.cart.index',
        'shop.api.checkout.cart.store',
        'shop.api.checkout.cart.destroy',
        'shop.search.index',
        'shop.compare.index',
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
        $response = $next($request);

        if (
            (bool) config('responsecache.enabled')
            || ! (bool) core()->getConfigData('lsc.configuration.cache_application.active')
        ) {
            LSCache::purgeAll(false);

            return $response;
        }

        if ($this->isGuestOnlyCacheEnabled($guard)) {
            return $this->setNoCacheHeaders($response);
        }

        $dynamicCacheResponse = $this->handleDynamicCache($request, $response);

        if ($dynamicCacheResponse !== null) {
            return $dynamicCacheResponse;
        }

        $route = $request->route();

        $routeName = $route?->getName();

        if (! in_array($routeName, $this->cacheRoutes, true)) {
            return $response;
        }

        $tags = $this->getRouteTags($routeName, $request->getPathInfo());

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

        $response->headers->set('Cache-Control', $lscacheControl);
        $response->headers->set('X-LiteSpeed-Cache-Control', $lscacheControl);
        $response->headers->set('X-LiteSpeed-Tag', implode(',', $tags));

        return $response;
    }

    /**
     * Check if guest-only cache is enabled and user is logged in.
     */
    private function isGuestOnlyCacheEnabled($guard): bool
    {
        return auth()->guard($guard)->check() && (bool) core()->getConfigData('lsc.configuration.cache_application.guest_only');
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
    private function getRouteTags($routeName, $routePathInfo): array
    {
        $slug = urldecode(trim($routePathInfo, '/'));
        $lastSegment = last(explode('/', $routePathInfo));

        return match ($routeName) {
            'shop.home.index'                => ['home'],
            'shop.cms.page'                  => ["page_$lastSegment"],
            'shop.product_or_category.index' => $this->getProductOrCategoryTags($slug),
            'shop.home.contact_us'           => ['contact'],
            'shop.search.index'              => ['search'],
            'shop.compare.index'             => ['compare'],
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
            return ['category_'.$slug];
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

            return ['product_'.$slug];
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

        return $response;
    }
}
