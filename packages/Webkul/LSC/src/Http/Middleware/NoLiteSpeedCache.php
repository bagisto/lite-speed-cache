<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webkul\LSC\Support\LiteSpeedDebug;

class NoLiteSpeedCache
{
    /**
     * Routes that are allowed to be cached.
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
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $routeName = $request->route()?->getName();

        if ($this->isShopStateRoute($request, $routeName)) {
            return $this->setNoCacheHeaders($response);
        }

        if ($routeName && in_array($routeName, $this->cacheRoutes, true)) {
            return LiteSpeedDebug::attachToResponse($response);
        }

        return $this->setNoCacheHeaders($response);
    }

    /**
     * Cart pages and APIs must never be LiteSpeed cached.
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

    /**
     * Apply browser-safe and LiteSpeed-safe no-cache headers.
     */
    private function setNoCacheHeaders($response)
    {
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        $response->headers->set('X-LiteSpeed-Cache-Control', 'no-cache');

        return LiteSpeedDebug::attachToResponse($response, [], 'no-cache');
    }
}
