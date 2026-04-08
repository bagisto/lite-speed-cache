<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webkul\LSC\Traits\UserCacheVariation;

/**
 * ESI Cache Middleware
 * 
 * Handles ESI (Edge Side Includes) requests with proper private cache headers.
 * This middleware ensures user-specific content is properly cached and isolated.
 */
class EsiCacheMiddleware
{
    use UserCacheVariation;

    /**
     * Handle an incoming ESI request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Set vary cookie before processing request
        $this->setVaryCookie();

        $response = $next($request);

        // Only apply ESI cache headers if LSC is enabled
        if (! $this->isLscEnabled()) {
            return $response;
        }

        // Skip if response already has cache control
        if ($response->headers->has('X-LiteSpeed-Cache-Control')) {
            return $response;
        }

        $ttl = $this->getEsiCacheTTL();
        
        // CRITICAL: Set private cache headers for ESI with session for user isolation
        $varyKey = $this->getVaryCookieName();
        $sessionCookie = $this->getSessionCookieName();
        $response->headers->set('X-LiteSpeed-Cache-Control', "private,max-age={$ttl},esi=on");
        $response->headers->set('X-LiteSpeed-Vary', "cookie={$varyKey},cookie={$sessionCookie},cookie=bagisto_locale,cookie=bagisto_currency");
        $response->headers->set('Cache-Control', "private, max-age={$ttl}");

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
