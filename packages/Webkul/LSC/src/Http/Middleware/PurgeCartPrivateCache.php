<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\LSC\Support\DebuggableLSCache as LSCache;

class PurgeCartPrivateCache
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (
            in_array($request->getMethod(), ['GET', 'HEAD'], true)
            || $response->getStatusCode() >= 400
        ) {
            return $response;
        }

        LSCache::purgeItems(['/api/checkout/cart']);
        ResponseCache::forget('/api/checkout/cart');

        return $response;
    }
}
