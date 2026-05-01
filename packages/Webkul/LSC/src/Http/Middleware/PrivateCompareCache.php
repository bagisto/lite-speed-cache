<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webkul\LSC\Support\PrivateCompareCache as PrivateCompareCacheSupport;

class PrivateCompareCache
{
    /**
     * Apply private LiteSpeed compare caching to safe compare read routes only.
     */
    public function handle(Request $request, Closure $next, string $scope = 'compare-api')
    {
        $response = $next($request);

        if (! PrivateCompareCacheSupport::shouldCache($request, $response)) {
            return $response;
        }

        return PrivateCompareCacheSupport::apply($response, $request, $scope);
    }
}
