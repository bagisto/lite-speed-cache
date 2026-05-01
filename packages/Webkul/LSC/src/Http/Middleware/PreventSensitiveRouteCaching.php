<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webkul\LSC\Support\CartCacheContext;
use Webkul\LSC\Support\LiteSpeedDebug;
use Webkul\LSC\Support\PrivateCartCache as PrivateCartCacheSupport;
use Webkul\LSC\Support\PrivateCompareCache as PrivateCompareCacheSupport;
use Webkul\LSC\Support\PrivateWishlistCache as PrivateWishlistCacheSupport;

class PreventSensitiveRouteCaching
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (
            $this->isPrivatelyCacheableCartRoute($request)
            && PrivateCartCacheSupport::shouldCache($request, $response)
        ) {
            $scope = match ((string) $request->route()?->getName()) {
                'shop.checkout.cart.index' => 'cart-page',
                'shop.api.checkout.cart.cross-sell.index' => 'cart-cross-sell',
                default => 'cart-api',
            };

            return PrivateCartCacheSupport::apply($response, $request, $scope);
        }

        if (
            $this->isPrivatelyCacheableCompareRoute($request)
            && PrivateCompareCacheSupport::shouldCache($request, $response)
        ) {
            $scope = (string) $request->route()?->getName() === 'shop.compare.index'
                ? 'compare-page'
                : 'compare-api';

            return PrivateCompareCacheSupport::apply($response, $request, $scope);
        }

        if (
            $this->isPrivatelyCacheableWishlistRoute($request)
            && PrivateWishlistCacheSupport::shouldCache($request, $response)
        ) {
            $scope = (string) $request->route()?->getName() === 'shop.customers.account.wishlist.index'
                ? 'wishlist-page'
                : 'wishlist-api';

            return PrivateWishlistCacheSupport::apply($response, $request, $scope);
        }

        if (! $this->shouldBypassCache($request)) {
            return $response;
        }

        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        $response->headers->set('X-LiteSpeed-Cache-Control', 'no-cache');

        return LiteSpeedDebug::attachToResponse($response, [], 'no-cache');
    }

    /**
     * Sensitive routes must never be cached, but public storefront pages may still be cached
     * for logged-in customers when they do not expose customer-specific state directly.
     */
    private function shouldBypassCache(Request $request): bool
    {
        if (! in_array($request->getMethod(), ['GET', 'HEAD'], true)) {
            return true;
        }

        $routeName = (string) $request->route()?->getName();

        if ($this->isPrivatelyCacheableCartRoute($request)) {
            return false;
        }

        if ($this->isPrivatelyCacheableCompareRoute($request)) {
            return false;
        }

        if ($this->isPrivatelyCacheableWishlistRoute($request)) {
            return false;
        }

        $adminPrefix = trim((string) config('app.admin_url', 'admin'), '/');
        $isAdminRoute = str_starts_with($routeName, 'admin.')
            || ($adminPrefix !== '' && ($request->is($adminPrefix) || $request->is($adminPrefix.'/*')));

        if ($isAdminRoute && auth()->guard('admin')->check()) {
            return true;
        }

        return $isAdminRoute
            || $request->is('login')
            || $request->is('logout')
            || $request->is('checkout')
            || $request->is('checkout/*')
            || $request->is('cart')
            || $request->is('cart/*')
            || $request->is('customer')
            || $request->is('customer/*')
            || $request->is('compare')
            || $request->is('api/checkout/cart')
            || $request->is('api/checkout/cart/*')
            || $request->is('api/compare-items')
            || $request->is('api/compare-items/*')
            || $request->is('api/customer/wishlist')
            || $request->is('api/customer/wishlist/*');
    }

    /**
     * Selected cart reads can be cached privately when LSC is enabled.
     */
    private function isPrivatelyCacheableCartRoute(Request $request): bool
    {
        if (! CartCacheContext::privateCacheEnabled()) {
            return false;
        }

        return in_array((string) $request->route()?->getName(), [
            'shop.checkout.cart.index',
            'shop.api.checkout.cart.index',
            'shop.api.checkout.cart.cross-sell.index',
        ], true);
    }

    /**
     * Selected compare reads can be cached privately when LSC is enabled.
     */
    private function isPrivatelyCacheableCompareRoute(Request $request): bool
    {
        if (! CartCacheContext::privateCacheEnabled()) {
            return false;
        }

        return in_array((string) $request->route()?->getName(), [
            'shop.compare.index',
            'shop.api.compare.index',
        ], true);
    }

    /**
     * Selected wishlist reads can be cached privately when LSC is enabled.
     */
    private function isPrivatelyCacheableWishlistRoute(Request $request): bool
    {
        if (! CartCacheContext::privateCacheEnabled()) {
            return false;
        }

        return in_array((string) $request->route()?->getName(), [
            'shop.customers.account.wishlist.index',
            'shop.api.customers.account.wishlist.index',
        ], true);
    }
}
