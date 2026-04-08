<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use LSCache;
use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\LSC\Traits\UserCacheVariation;

class PreventCartApiCache
{
    use UserCacheVariation;

    /**
     * Prevent cart, compare, and wishlist responses from being cached across sessions.
     * When ESI is enabled, allows private caching for GET requests.
     */
    public function handle(Request $request, Closure $next)
    {
        // Set vary cookie for user isolation
        $this->setVaryCookie();

        if ($this->isShopStateRequest($request)) {
            // Purge user-specific cache when cart state changes
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

        // For GET requests with ESI enabled, allow private caching
        // CRITICAL: Do NOT cache if no valid vary cookie (first request)
        if ($this->isEsiEnabled() && $this->canUsePrivateCache($request) && $this->hasValidVaryCookie()) {
            return $this->applyPrivateCacheHeaders($response);
        }

        // For POST/DELETE requests, first requests, or when ESI is disabled, use no-cache
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('X-LiteSpeed-Cache-Control', 'no-cache');
        $response->headers->set('Pragma', 'no-cache');

        return $response;
    }

    /**
     * Check if this request can use private caching.
     */
    private function canUsePrivateCache(Request $request): bool
    {
        // Only GET/HEAD requests
        if (! in_array($request->getMethod(), ['GET', 'HEAD'])) {
            return false;
        }

        // Only for read operations (api/checkout/cart GET)
        return $request->is('api/checkout/cart')
            || $request->is('checkout/cart')
            || $request->is('compare')
            || $request->is('customer/account/wishlist');
    }

    /**
     * Apply private cache headers for user-isolated content.
     * CRITICAL: Includes laravel_session to prevent cross-user cache leakage.
     */
    private function applyPrivateCacheHeaders($response)
    {
        $ttl = $this->getPrivateCacheTTL();
        $varyKey = $this->getVaryCookieName();
        $sessionCookie = $this->getSessionCookieName();
        $userTag = $this->getUserCacheTag();

        $response->headers->set('X-LiteSpeed-Cache-Control', "private,max-age={$ttl}");
        $response->headers->set('X-LiteSpeed-Vary', "cookie={$varyKey},cookie={$sessionCookie},cookie=bagisto_locale,cookie=bagisto_currency");
        $response->headers->set('X-LiteSpeed-Tag', "cart,{$userTag}");
        $response->headers->set('Cache-Control', "private, max-age={$ttl}");
        $response->headers->set('Vary', 'Cookie');

        // Remove no-cache headers if they were set
        $response->headers->remove('Pragma');

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
