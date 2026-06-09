<?php

namespace Webkul\LSC\Support;

use Illuminate\Http\Request;
use Webkul\Customer\Repositories\CompareItemRepository;

class PrivateCompareCache
{
    /**
     * Apply private LiteSpeed cache headers for compare responses.
     */
    public static function apply(mixed $response, Request $request, string $scope): mixed
    {
        $ttl = max(60, (int) config('lscache.private_ttl', 300));
        $tags = array_merge(
            CartCacheContext::responseTagsForFamily('compare-private', $scope, $request),
            self::productTags($request)
        );
        $cacheControl = CartCacheContext::withEsi('private,max-age='.$ttl);
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
     * Catalog content tags for every product in the compare list.
     *
     * Customers store compare items in the database; guests pass them as the
     * "product_ids" request input. Tagging the cached compare list with
     * "product_{id}" lets the existing Product listener purge invalidate it
     * whenever one of its products changes — no extra listener code needed.
     */
    protected static function productTags(Request $request): array
    {
        try {
            if ($customerId = auth()->guard('customer')->id()) {
                $ids = app(CompareItemRepository::class)
                    ->findByField('customer_id', $customerId)
                    ->pluck('product_id')
                    ->all();
            } else {
                $ids = (array) $request->input('product_ids', []);
            }

            return CartCacheContext::productTags($ids);
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * Determine whether a compare response is safe to store privately.
     */
    public static function shouldCache(Request $request, mixed $response): bool
    {
        return (bool) core()->getConfigData('lsc.configuration.cache_application.active')
            && ! (bool) config('responsecache.enabled')
            && in_array($request->getMethod(), ['GET', 'HEAD'], true)
            && method_exists($response, 'getStatusCode')
            && method_exists($response, 'getContent')
            && $response->getStatusCode() === 200
            && $response->getContent() !== '';
    }
}
