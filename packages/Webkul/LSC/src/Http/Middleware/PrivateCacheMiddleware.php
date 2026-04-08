<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webkul\LSC\Traits\UserCacheVariation;

/**
 * Private Cache Middleware
 * 
 * Applies private (user-isolated) caching to routes like cart page.
 * Uses X-LiteSpeed-Vary to ensure cache separation between users.
 */
class PrivateCacheMiddleware
{
    use UserCacheVariation;

    /**
     * Routes that should use private caching.
     *
     * @var array
     */
    protected $privateCacheRoutes = [
        'shop.checkout.cart.index',
        'shop.customers.account.wishlist.index',
        'shop.compare.index',
    ];

    /**
     * URL patterns that should use private caching.
     *
     * @var array
     */
    protected $privateCachePatterns = [
        'checkout/cart',
        'customer/account/wishlist',
        'compare',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Set vary cookie for cache isolation
        $this->setVaryCookie();

        $response = $next($request);

        // Only apply if LSC is enabled
        if (! $this->isLscEnabled()) {
            return $response;
        }

        // Check if this is a private cache route
        if (! $this->isPrivateCacheRoute($request)) {
            return $response;
        }

        // Only cache successful GET/HEAD requests
        if (! $this->isCacheableRequest($request, $response)) {
            return $this->setNoCacheHeaders($response);
        }

        return $this->applyPrivateCacheHeaders($request, $response);
    }

    /**
     * Check if the current route should use private caching.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isPrivateCacheRoute(Request $request): bool
    {
        $routeName = $request->route()?->getName();

        // Check route name
        if ($routeName && in_array($routeName, $this->privateCacheRoutes, true)) {
            return true;
        }

        // Check URL pattern
        foreach ($this->privateCachePatterns as $pattern) {
            if ($request->is($pattern) || $request->is($pattern . '/*')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the request is cacheable.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $response
     * @return bool
     */
    protected function isCacheableRequest(Request $request, $response): bool
    {
        return in_array($request->getMethod(), ['GET', 'HEAD'])
            && $response->getStatusCode() === 200;
    }

    /**
     * Apply private cache headers to the response.
     * CRITICAL: Includes laravel_session to prevent cross-user cache leakage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $response
     * @return mixed
     */
    protected function applyPrivateCacheHeaders(Request $request, $response)
    {
        $ttl = $this->getPrivateCacheTTL();
        $varyKey = $this->getVaryCookieName();
        $sessionCookie = $this->getSessionCookieName();
        $tags = $this->generateCacheTags($request);

        // CRITICAL: Include session cookie for proper user isolation
        $response->headers->set('X-LiteSpeed-Cache-Control', "private,max-age={$ttl}");
        $response->headers->set('X-LiteSpeed-Vary', "cookie={$varyKey},cookie={$sessionCookie},cookie=bagisto_locale,cookie=bagisto_currency");
        
        if (! empty($tags)) {
            $response->headers->set('X-LiteSpeed-Tag', implode(',', $tags));
        }

        // Standard cache headers
        $response->headers->set('Cache-Control', "private, max-age={$ttl}");
        $response->headers->set('Vary', 'Cookie');

        return $response;
    }

    /**
     * Generate cache tags for the current request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function generateCacheTags(Request $request): array
    {
        $tags = [$this->getUserCacheTag()];
        $routeName = $request->route()?->getName();

        if ($request->is('checkout/cart') || $request->is('checkout/cart/*')) {
            $tags[] = 'cart';
            $tags[] = 'cart-page';
        }

        if ($request->is('compare') || $routeName === 'shop.compare.index') {
            $tags[] = 'compare';
            $tags[] = 'compare-page';
        }

        if ($request->is('customer/account/wishlist') || $routeName === 'shop.customers.account.wishlist.index') {
            $tags[] = 'wishlist';
            $tags[] = 'wishlist-page';
        }

        return $tags;
    }

    /**
     * Set no-cache headers for non-cacheable responses.
     *
     * @param  mixed  $response
     * @return mixed
     */
    protected function setNoCacheHeaders($response)
    {
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('X-LiteSpeed-Cache-Control', 'no-cache');

        return $response;
    }

    /**
     * Check if LiteSpeed Cache is enabled.
     *
     * @return bool
     */
    protected function isLscEnabled(): bool
    {
        return (bool) core()->getConfigData('lsc.configuration.cache_application.active');
    }
}
