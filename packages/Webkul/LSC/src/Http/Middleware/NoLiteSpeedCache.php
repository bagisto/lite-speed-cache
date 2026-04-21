<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
     * Cart pages and APIs must never be LiteSpeed cached.
     */
    private function isShopStateRoute(Request $request, ?string $routeName): bool
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
