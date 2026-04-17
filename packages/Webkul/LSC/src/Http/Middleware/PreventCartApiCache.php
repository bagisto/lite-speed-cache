<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Litespeed\LSCache\LSCache;

class PreventCartApiCache
{
    /**
     * Prevent cart routes from being stored or served from LiteSpeed cache.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->isCartRequest($request)) {
            LSCache::purgeItems($this->getPurgeItems($request));
        }

        $response = $next($request);

        if (! $this->isCartRequest($request)) {
            return $response;
        }

        $response->headers->set('Cache-Control', 'private, no-cache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('X-LiteSpeed-Cache-Control', 'no-cache');
        $response->headers->remove('X-LiteSpeed-Tag');

        return $response;
    }

    /**
     * Match cart page and cart API by path so publish drift does not matter.
     */
    private function isCartRequest(Request $request): bool
    {
        return $request->is('api/checkout/cart')
            || $request->is('api/checkout/cart/*')
            || $request->is('checkout/cart')
            || $request->is('checkout/cart/*');
    }

    /**
     * Purge the base cart endpoint and current path.
     */
    private function getPurgeItems(Request $request): array
    {
        $items = ['/api/checkout/cart'];
        $currentPath = '/'.$request->path();

        if (! in_array($currentPath, $items, true)) {
            $items[] = $currentPath;
        }

        return $items;
    }
}
