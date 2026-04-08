<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Litespeed\LSCache\LSCacheMiddleware as BaseLSCacheMiddleware;
use LSCache;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\LSC\Traits\UserCacheVariation;

class LSCacheHeaders extends BaseLSCacheMiddleware
{
    use UserCacheVariation;

    /**
     * Routes eligible for public caching.
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
     * Routes that should use private (user-isolated) caching.
     *
     * @var array
     */
    protected $privateCacheRoutes = [
        'shop.checkout.cart.index',
        'shop.api.checkout.cart.index',
    ];

    /**
     * Routes that should NEVER be cached (security-sensitive).
     *
     * @var array
     */
    protected $neverCacheRoutes = [
        'shop.checkout.onepage.index',
        'shop.checkout.success',
        'shop.customer.session.create',
        'shop.customer.session.store',
        'shop.customer.session.destroy',
        'shop.customers.register.index',
        'shop.customers.register.create',
    ];

    /**
     * ESI route prefixes.
     *
     * @var array
     */
    protected $esiRoutes = [
        'shop.lsc.esi.',
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

        // Set vary cookie for user isolation
        $this->setVaryCookie();

        $response = $next($request);
        
        if (
            (bool) config('responsecache.enabled')
            || ! (bool) core()->getConfigData('lsc.configuration.cache_application.active')
        ) {
            return $response;
        }

        // Check if this is an ESI route - let ESI middleware handle it
        if ($this->isEsiRoute($request, $routeName)) {
            return $response;
        }

        // Check if this is a never-cache route (checkout, auth, etc.)
        if ($this->isNeverCacheRoute($request, $routeName)) {
            return $this->setNoCacheHeaders($response);
        }

        // Check if this route should use private caching
        if ($this->isPrivateCacheRoute($request, $routeName)) {
            return $this->handlePrivateCache($request, $response);
        }

        if ($this->isGuestOnlyCacheEnabled($guard)) {
            return $this->setNoCacheHeaders($response);
        }

        $dynamicCacheResponse = $this->handleDynamicCache($request, $response);

        if ($dynamicCacheResponse !== null) {
            return $dynamicCacheResponse;
        }

        if ($this->isShopStateRoute($request, $routeName)) {
            if ($this->shouldInvalidateHomeCache($routeName)) {
                LSCache::purgeTags(['home', 'home-header']);
            }

            // For cart/wishlist/compare pages, use private cache instead of no-cache
            if ($this->shouldUsePrivateCache($request, $routeName)) {
                return $this->handlePrivateCache($request, $response);
            }

            return $this->setNoCacheHeaders($response);
        }

        if (! in_array($routeName, $this->cacheRoutes, true)) {
            return $this->setNoCacheHeaders($response);
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
        
        // CRITICAL: Vary by locale and currency for proper translations
        $response->headers->set('X-LiteSpeed-Vary', $this->getPublicCacheVaryValue());
        $response->headers->set('Vary', 'Accept-Encoding, Cookie');

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

    /**
     * Check if this is an ESI route.
     */
    private function isEsiRoute($request, ?string $routeName): bool
    {
        // Check route name prefix
        foreach ($this->esiRoutes as $prefix) {
            if (str_starts_with((string) $routeName, $prefix)) {
                return true;
            }
        }

        // Check URL pattern
        return $request->is('esi/*') || $request->is('api/esi/*');
    }

    /**
     * Check if this route should never be cached (security-sensitive).
     */
    private function isNeverCacheRoute($request, ?string $routeName): bool
    {
        // Check explicit never-cache routes
        if ($routeName && in_array($routeName, $this->neverCacheRoutes, true)) {
            return true;
        }

        // Check patterns that should never be cached
        return $request->is('checkout/onepage')
            || $request->is('checkout/onepage/*')
            || $request->is('checkout/success')
            || $request->is('customer/session')
            || $request->is('customer/session/*')
            || $request->is('customer/login')
            || $request->is('customer/logout')
            || $request->is('customer/register')
            || $request->is('customer/forgot-password')
            || $request->is('paypal/*')
            || $request->is('admin/*');
    }

    /**
     * Check if this route should use private caching.
     */
    private function isPrivateCacheRoute($request, ?string $routeName): bool
    {
        // Only enable if ESI/private cache is configured
        if (! $this->isEsiEnabled()) {
            return false;
        }

        // Check explicit private cache routes
        if ($routeName && in_array($routeName, $this->privateCacheRoutes, true)) {
            return true;
        }

        // Check URL patterns for private cache
        return $request->is('checkout/cart')
            || $request->is('api/checkout/cart');
    }

    /**
     * Check if route should use private cache (for state routes).
     */
    private function shouldUsePrivateCache($request, ?string $routeName): bool
    {
        // Only enable if ESI/private cache is configured
        if (! $this->isEsiEnabled()) {
            return false;
        }

        // Cart page, compare, wishlist can use private cache
        return $request->is('checkout/cart')
            || $request->is('compare')
            || $request->is('customer/account/wishlist');
    }

    /**
     * Handle private caching for user-isolated content.
     * 
     * CRITICAL SECURITY: This method ensures proper user isolation to prevent cache leakage.
     */
    private function handlePrivateCache($request, $response)
    {
        // Only cache successful GET/HEAD requests
        if (
            ! in_array($request->getMethod(), ['GET', 'HEAD'])
            || $response->getStatusCode() !== 200
        ) {
            return $this->setNoCacheHeaders($response);
        }

        // CRITICAL: Do NOT cache first request (no valid vary cookie)
        // This prevents cache leakage when vary cookie is being set
        if ($this->isFirstRequest()) {
            return $this->setNoCacheHeaders($response);
        }

        $ttl = $this->getPrivateCacheTTL();
        $tags = $this->getPrivateCacheTags($request);

        // Set LiteSpeed private cache headers with FULL vary (including session)
        $response->headers->set('X-LiteSpeed-Cache-Control', "private,max-age={$ttl}");
        $response->headers->set('X-LiteSpeed-Vary', $this->getPrivateCacheVaryValue());
        
        if (! empty($tags)) {
            $response->headers->set('X-LiteSpeed-Tag', implode(',', $tags));
        }

        // Standard cache headers
        $response->headers->set('Cache-Control', "private, max-age={$ttl}");
        $response->headers->set('Vary', 'Cookie');

        return $response;
    }

    /**
     * Get cache tags for private cache routes.
     */
    private function getPrivateCacheTags($request): array
    {
        $tags = [$this->getUserCacheTag()];

        if ($request->is('checkout/cart') || $request->is('checkout/cart/*') || $request->is('api/checkout/cart')) {
            $tags[] = 'cart';
            $tags[] = 'cart-page';
        }

        if ($request->is('compare')) {
            $tags[] = 'compare';
        }

        if ($request->is('customer/account/wishlist')) {
            $tags[] = 'wishlist';
        }

        return $tags;
    }

    /**
     * Get vary value for public cache routes.
     * MUST include locale and currency cookies for proper translations.
     *
     * @return string
     */
    private function getPublicCacheVaryValue(): string
    {
        $sessionCookie = $this->getSessionCookieName();
        
        // Include session cookie for user state awareness + locale + currency
        return "cookie={$sessionCookie},cookie=bagisto_locale,cookie=bagisto_currency";
    }

    /**
     * Get vary value for private cache routes.
     * Includes user isolation + session + locale + currency.
     * 
     * CRITICAL SECURITY: laravel_session MUST be included to prevent cross-user cache leakage.
     *
     * @return string
     */
    private function getPrivateCacheVaryValue(): string
    {
        $varyKey = $this->getVaryCookieName();
        $sessionCookie = $this->getSessionCookieName();
        
        // CRITICAL: Include BOTH lsc_vary_key AND laravel_session for proper isolation
        // This ensures cache is unique per user AND per session
        return "cookie={$varyKey},cookie={$sessionCookie},cookie=bagisto_locale,cookie=bagisto_currency";
    }

    /**
     * Check if this is the first request (no vary cookie exists).
     * First requests should NOT be cached to prevent cache leakage.
     *
     * @return bool
     */
    private function isFirstRequest(): bool
    {
        return ! $this->hasValidVaryCookie();
    }
}
