<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webkul\LSC\Support\LiteSpeedDebug;

class AdaptiveCompareCache
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (
            ! in_array($request->getMethod(), ['GET', 'HEAD'], true)
            || ! $response->getContent()
            || $response->getStatusCode() !== 200
        ) {
            return $response;
        }

        $ttl = (int) (env('LSCACHE_DEFAULT_TTL', core()->getConfigData('lsc.configuration.cache_application.default_ttl')));
        $ttl = $ttl > 0 ? $ttl : 3600;

        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        if (auth()->guard('customer')->check()) {
            $response->headers->set('X-LiteSpeed-Cache-Control', "private,max-age={$ttl}");
            $response->headers->set('X-LiteSpeed-Vary', 'cookie='.config('session.cookie'));

            return LiteSpeedDebug::attachToResponse($response, ['compare-private'], "private,max-age={$ttl}");
        }

        $response->headers->set('X-LiteSpeed-Cache-Control', "public,max-age={$ttl}");
        $response->headers->set('X-LiteSpeed-Tag', 'compare');

        return LiteSpeedDebug::attachToResponse($response, ['compare'], "public,max-age={$ttl}");
    }
}
