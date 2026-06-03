<?php

namespace Webkul\LSC\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\LSC\Support\CartCacheContext;
use Webkul\LSC\Support\DebuggableLSCache as LSCache;

class PurgeCartPrivateCache
{
    /**
     * Purge all cart private-cache variants for the current context after a successful write.
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

        $privateTags = array_merge(
            CartCacheContext::currentPrivateTags($request),
            // ESI mini-cart count badge for the current context.
            CartCacheContext::currentPrivateTagsForFamily('cart-count', ['cart-count'], $request)
        );
        $urls = [
            '/api/checkout/cart',
            '/api/checkout/cart/cross-sell',
            '/checkout/cart',
        ];

        LSCache::purgePrivateTags($privateTags);
        LSCache::purgeItems($urls);

        $purgeParts = array_merge(
            ['stale', 'private'],
            array_map(static fn (string $tag): string => 'tag='.$tag, $privateTags),
            $urls
        );

        $response->headers->set('X-LiteSpeed-Purge', implode(',', $purgeParts));

        ResponseCache::forget('/api/checkout/cart');
        ResponseCache::forget('/api/checkout/cart/cross-sell');
        ResponseCache::forget('/checkout/cart');

        return $response;
    }
}
