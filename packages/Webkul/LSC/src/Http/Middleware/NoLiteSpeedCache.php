<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webkul\LSC\Traits\UserCacheVariation;

class NoLiteSpeedCache
{
    use UserCacheVariation;

    /**
     * Routes that are allowed to be publicly cached.
     */
    protected $cacheRoutes = [
        'shop.home.index',
        'shop.cms.page',
        'shop.product_or_category.index',
        'shop.home.contact_us',
        'shop.search.index',
    ];

    /**
     * Routes that can use private caching when ESI is enabled.
     */
    protected $privateCacheRoutes = [
        'shop.checkout.cart.index',
        'shop.api.checkout.cart.index',
        'shop.compare.index',
        'shop.customers.account.wishlist.index',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $routeName = $request->route()?->getName();

        // Allow private caching for cart/compare/wishlist if ESI is enabled
        if ($this->isEsiEnabled() && $this->isPrivateCacheRoute($request, $routeName)) {
            return $this->handlePrivateCache($response);
        }

        // Check if this is a shop state route that needs special handling
        if ($this->isShopStateRoute($request, $routeName)) {
            // If ESI is enabled, allow private cache for certain routes
            if ($this->isEsiEnabled() && $this->canUsePrivateCache($request, $routeName)) {
                return $this->handlePrivateCache($response);
            }

            $response->headers->set('X-LiteSpeed-Cache-Control', 'no-cache');

            return $response;
        }

        if ($routeName && in_array($routeName, $this->cacheRoutes, true)) {
            return $response;
        }

        $response->headers->set('X-LiteSpeed-Cache-Control', 'no-cache');

        return $response;
    }

    /**
     * Check if route can use private caching.
     */
    private function isPrivateCacheRoute(Request $request, ?string $routeName): bool
    {
        if ($routeName && in_array($routeName, $this->privateCacheRoutes, true)) {
            return true;
        }

        return $request->is('checkout/cart')
            || $request->is('compare')
            || $request->is('customer/account/wishlist');
    }

    /**
     * Check if route can use private cache (subset of shop state routes).
     */
    private function canUsePrivateCache(Request $request, ?string $routeName): bool
    {
        // Only GET requests on specific pages
        if (! in_array($request->getMethod(), ['GET', 'HEAD'])) {
            return false;
        }

        return $request->is('checkout/cart')
            || $request->is('compare')
            || $request->is('customer/account/wishlist');
    }

    /**
     * Apply private cache headers.
     * CRITICAL: Includes laravel_session to prevent cross-user cache leakage.
     */
    private function handlePrivateCache($response)
    {
        $ttl = $this->getPrivateCacheTTL();
        $varyKey = $this->getVaryCookieName();
        $sessionCookie = $this->getSessionCookieName();

        $response->headers->set('X-LiteSpeed-Cache-Control', "private,max-age={$ttl}");
        $response->headers->set('X-LiteSpeed-Vary', "cookie={$varyKey},cookie={$sessionCookie},cookie=bagisto_locale,cookie=bagisto_currency");
        $response->headers->set('Cache-Control', "private, max-age={$ttl}");

        return $response;
    }

    /**
     * Cart pages and APIs must never be LiteSpeed cached (unless private cache is enabled).
     */
    private function isShopStateRoute(Request $request, ?string $routeName): bool
    {
        return str_starts_with((string) $routeName, 'shop.api.checkout.cart.')
            || str_starts_with((string) $routeName, 'shop.api.compare.')
            || str_starts_with((string) $routeName, 'shop.api.customers.account.wishlist.')
            || $routeName === 'shop.checkout.cart.index'
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
}
