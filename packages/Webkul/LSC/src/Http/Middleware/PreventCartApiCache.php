<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use LSCache;
use Spatie\ResponseCache\Facades\ResponseCache;

class PreventCartApiCache
{
    /**
     * Prevent cart, compare, and wishlist responses from being cached across sessions.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->isShopStateRequest($request)) {
            LSCache::purgeItems($this->getStateCacheItems($request));

            if ($this->isStateApiRequest($request)) {
                foreach ($this->getResponseCacheKeys($request) as $cacheKey) {
                    ResponseCache::forget($cacheKey);
                }

                $request->attributes->set('responsecache.doNotCache', true);
            }
        }

        $response = $next($request);

        if (! $this->isShopStateRequest($request)) {
            return $response;
        }

        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('X-LiteSpeed-Cache-Control', 'no-cache');
        $response->headers->set('Pragma', 'no-cache');

        return $response;
    }

    /**
     * Match stateful API endpoints regardless of route definition source.
     */
    private function isStateApiRequest(Request $request): bool
    {
        return $request->is('api/checkout/cart')
            || $request->is('api/checkout/cart/*')
            || $request->is('api/compare-items')
            || $request->is('api/compare-items/*')
            || $request->is('api/customer/wishlist')
            || $request->is('api/customer/wishlist/*');
    }

    /**
     * Match cart, compare, and wishlist pages and APIs.
     */
    private function isShopStateRequest(Request $request): bool
    {
        return $this->isStateApiRequest($request)
            || $request->is('checkout/cart')
            || $request->is('checkout/cart/*')
            || $request->is('compare')
            || $request->is('customer/account/wishlist');
    }

    /**
     * Purge the base state endpoint and the current path.
     */
    private function getStateCacheItems(Request $request): array
    {
        $paths = match (true) {
            $request->is('api/checkout/cart'), $request->is('api/checkout/cart/*') => ['/api/checkout/cart'],
            $request->is('api/compare-items'), $request->is('api/compare-items/*') => ['/api/compare-items'],
            $request->is('api/customer/wishlist'), $request->is('api/customer/wishlist/*') => ['/api/customer/wishlist'],
            $request->is('compare') => ['/compare'],
            $request->is('customer/account/wishlist') => ['/customer/account/wishlist'],
            default => [],
        };
        $currentPath = '/'.$request->path();

        if (! in_array($currentPath, $paths, true)) {
            $paths[] = $currentPath;
        }

        return $paths;
    }

    /**
     * Response cache keys for stateful APIs.
     */
    private function getResponseCacheKeys(Request $request): array
    {
        return match (true) {
            $request->is('api/checkout/cart'), $request->is('api/checkout/cart/*') => ['/api/checkout/cart'],
            $request->is('api/compare-items'), $request->is('api/compare-items/*') => ['/api/compare-items'],
            $request->is('api/customer/wishlist'), $request->is('api/customer/wishlist/*') => ['/api/customer/wishlist'],
            default => [],
        };
    }
}
