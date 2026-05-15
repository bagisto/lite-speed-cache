<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webkul\LSC\Support\PrivateWishlistCache as PrivateWishlistCacheSupport;

class PrivateWishlistCache
{
    /**
     * Apply private LiteSpeed wishlist caching to safe wishlist read routes only.
     */
    public function handle(Request $request, Closure $next, string $scope = 'wishlist-api')
    {
        $response = $next($request);

        if (! PrivateWishlistCacheSupport::shouldCache($request, $response)) {
            return $response;
        }

        return PrivateWishlistCacheSupport::apply($response, $request, $scope);
    }
}
