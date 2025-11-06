<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NoLiteSpeedCache
{
    /**
     * Routes that are allowed to be cached.
     */
    protected $cacheRoutes = [
        'shop.home.index',
        'shop.cms.page',
        'shop.product_or_category.index',
        'shop.home.contact_us',
        'shop.api.checkout.cart.index',
        'shop.api.checkout.cart.store',
        'shop.api.checkout.cart.destroy',
        'shop.search.index',
        'shop.compare.index',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (in_array($request->route()->getName(), $this->cacheRoutes)) {
            return $response;
        }

        $response->headers->set('X-LiteSpeed-Cache-Control', 'no-cache');

        return $response;
    }
}
