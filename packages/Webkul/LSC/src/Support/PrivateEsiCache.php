<?php

namespace Webkul\LSC\Support;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

class PrivateEsiCache
{
    /**
     * Apply private LiteSpeed cache headers to an ESI fragment response.
     *
     * Mirrors PrivateCartCache::apply but for per-user header fragments
     * (login dropdowns, mini-cart count) served as ESI includes. Each fragment:
     *
     *  - is cached privately, keyed per customer/guest via the `lsc_private`
     *    cookie (X-LiteSpeed-Vary), with its own short TTL;
     *  - carries a context-scoped purge tag ("{scope}-{context}") so it can be
     *    invalidated on the relevant change (login/logout, cart mutation);
     *  - sets the `lsc_private` cookie so the next request keys correctly even
     *    when the cookie was not present on the parent (public) page — the
     *    parent page must never set a per-user cookie, since it is shared.
     *
     *  LiteSpeed discards private ESI blocks larger than 8 KB, so keep the
     *  rendered fragments lean.
     */
    public static function apply(mixed $response, Request $request, string $scope, array $extraTags = []): mixed
    {
        $ttl = CartCacheContext::privateTtl();

        $tags = array_values(array_unique(array_merge(
            CartCacheContext::responseTags($scope, $request),
            $extraTags
        )));

        $cacheControl = 'private,max-age='.$ttl;

        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        $response->headers->set('X-LiteSpeed-Cache-Control', $cacheControl);
        $response->headers->set('X-LiteSpeed-Tag', implode(',', $tags));
        $response->headers->set('X-LiteSpeed-Vary', CartCacheContext::varyHeader($request));
        $response->headers->setCookie(new Cookie(
            CartCacheContext::privateCookieName(),
            CartCacheContext::privateCookieValue($request),
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
}
