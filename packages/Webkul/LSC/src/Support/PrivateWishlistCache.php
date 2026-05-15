<?php

namespace Webkul\LSC\Support;

use Illuminate\Http\Request;

class PrivateWishlistCache
{
    /**
     * Apply private LiteSpeed cache headers for wishlist responses.
     */
    public static function apply(mixed $response, Request $request, string $scope): mixed
    {
        $ttl = max(60, (int) config('lscache.private_ttl', 300));
        $tags = CartCacheContext::responseTagsForFamily('wishlist-private', $scope, $request);
        $cacheControl = 'private,max-age='.$ttl;
        $privateCookieName = CartCacheContext::privateCookieName();
        $privateCookieValue = CartCacheContext::privateCookieValue($request);

        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        $response->headers->set('X-LiteSpeed-Cache-Control', $cacheControl);
        $response->headers->set('X-LiteSpeed-Tag', implode(',', $tags));
        $response->headers->set('X-LiteSpeed-Vary', 'cookie='.$privateCookieName);
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie(
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
     * Determine whether a wishlist response is safe to store privately.
     */
    public static function shouldCache(Request $request, mixed $response): bool
    {
        return (bool) core()->getConfigData('lsc.configuration.cache_application.active')
            && auth()->guard('customer')->check()
            && ! (bool) config('responsecache.enabled')
            && in_array($request->getMethod(), ['GET', 'HEAD'], true)
            && method_exists($response, 'getStatusCode')
            && method_exists($response, 'getContent')
            && $response->getStatusCode() === 200
            && $response->getContent() !== '';
    }
}
