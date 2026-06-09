<?php

namespace Webkul\LSC\Support;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Webkul\Checkout\Facades\Cart;

class PrivateCartCache
{
    /**
     * Apply private LiteSpeed cache headers for cart responses.
     */
    public static function apply(mixed $response, Request $request, string $scope): mixed
    {
        $ttl = CartCacheContext::privateTtl();
        $tags = array_merge(
            CartCacheContext::responseTags($scope, $request),
            self::productTags()
        );
        $cacheControl = CartCacheContext::withEsi('private,max-age='.$ttl);
        $privateCookieName = CartCacheContext::privateCookieName();
        $privateCookieValue = CartCacheContext::privateCookieValue($request);

        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        $response->headers->set('X-LiteSpeed-Cache-Control', $cacheControl);
        $response->headers->set('X-LiteSpeed-Tag', implode(',', $tags));
        $response->headers->set('X-LiteSpeed-Vary', CartCacheContext::varyHeader($request));
        $response->headers->setCookie(new Cookie(
            $privateCookieName,
            $privateCookieValue,
            0,
            '/',
            config('session.domain'),
            (bool) config('session.secure'),
            false,
            false,
            config('session.same_site', 'lax')
        ));

        return LiteSpeedDebug::attachToResponse($response, $tags, $cacheControl);
    }

    /**
     * Catalog content tags for every product currently in the cart.
     *
     * Tagging the cached cart with "product_{id}" lets the existing Product
     * listener purge invalidate this cart whenever one of its products
     * changes — no extra listener code needed.
     */
    protected static function productTags(): array
    {
        try {
            $cart = Cart::getCart();

            if (! $cart) {
                return [];
            }

            $ids = [];

            foreach ($cart->items as $item) {
                $ids[] = $item->product_id;

                foreach ($item->children as $child) {
                    $ids[] = $child->product_id;
                }
            }

            return CartCacheContext::productTags($ids);
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * Determine whether a cart response is safe to store privately.
     */
    public static function shouldCache(Request $request, mixed $response): bool
    {
        return CartCacheContext::privateCacheEnabled()
            && ! (bool) config('responsecache.enabled')
            && in_array($request->getMethod(), ['GET', 'HEAD'], true)
            && method_exists($response, 'getStatusCode')
            && method_exists($response, 'getContent')
            && $response->getStatusCode() === 200
            && $response->getContent() !== '';
    }
}
