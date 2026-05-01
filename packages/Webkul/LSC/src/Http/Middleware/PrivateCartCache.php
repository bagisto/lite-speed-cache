<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webkul\LSC\Support\PrivateCartCache as PrivateCartCacheSupport;

class PrivateCartCache
{
    /**
     * Apply private LiteSpeed cart caching to safe cart read routes only.
     */
    public function handle(Request $request, Closure $next, string $scope = 'cart-api')
    {
        $response = $next($request);

        if (! PrivateCartCacheSupport::shouldCache($request, $response)) {
            return $response;
        }

        return PrivateCartCacheSupport::apply($response, $request, $scope);
    }
}
