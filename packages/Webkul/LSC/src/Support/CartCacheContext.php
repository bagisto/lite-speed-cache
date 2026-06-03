<?php

namespace Webkul\LSC\Support;

use Illuminate\Http\Request;

class CartCacheContext
{
    /**
     * LiteSpeed-recognized private cookie used for cart isolation.
     */
    private const PRIVATE_COOKIE = 'lsc_private';

    /**
     * Unencrypted cookie used to vary public cache by customer group.
     */
    private const GROUP_COOKIE = 'lsc_customer_group';

    /**
     * Customer group code used for unauthenticated visitors.
     */
    private const GUEST_GROUP = 'guest';

    /**
     * Cart-specific private cache scopes that should move together.
     */
    private const SCOPES = [
        'cart-api',
        'cart-cross-sell',
        'cart-page',
    ];

    /**
     * Determine whether the package should privately cache cart reads.
     */
    public static function privateCacheEnabled(): bool
    {
        return (bool) core()->getConfigData('lsc.configuration.cache_application.active');
    }

    /**
     * Determine whether ESI (Edge Side Includes) should be used.
     *
     * Requires both the ESI flag (LSCACHE_ESI_ENABLED, only meaningful on
     * LiteSpeed Web Server Enterprise — OpenLiteSpeed has no ESI support) and
     * LSC itself to be active. Templates branch on this to emit <esi:include>
     * tags instead of the AJAX/Vue hole-punching fallback.
     */
    public static function esiEnabled(): bool
    {
        return (bool) config('lscache.esi') && self::privateCacheEnabled();
    }

    /**
     * Resolve the cart private cache TTL.
     */
    public static function privateTtl(): int
    {
        return max(60, (int) config('lscache.private_ttl', 300));
    }

    /**
     * Build the vary header needed for per-session / per-customer isolation.
     */
    public static function varyHeader(?Request $request = null): string
    {
        return 'cookie='.self::privateCookieName();
    }

    /**
     * Cart vary cookie names.
     */
    public static function varyCookies(): array
    {
        return [self::privateCookieName()];
    }

    /**
     * The unencrypted cookie name LiteSpeed should use for private cache lookup.
     */
    public static function privateCookieName(): string
    {
        return self::PRIVATE_COOKIE;
    }

    /**
     * The unencrypted cookie name used to vary public cache by customer group.
     */
    public static function groupCookieName(): string
    {
        return self::GROUP_COOKIE;
    }

    /**
     * Resolve the customer group code for the current visitor.
     */
    public static function currentGroupCode(): string
    {
        $customer = auth()->guard('customer')->user();

        return $customer?->group?->code ?: self::GUEST_GROUP;
    }

    /**
     * Vary header for public cached pages: customer group, locale and currency.
     */
    public static function publicVaryHeader(): string
    {
        return 'cookie='.implode(',', [
            self::GROUP_COOKIE,
            'bagisto_locale',
            'bagisto_currency',
        ]);
    }

    /**
     * Stable private cookie value for the current cart context.
     */
    public static function privateCookieValue(?Request $request = null): string
    {
        $request ??= request();

        $customerId = auth()->guard('customer')->id();
        $locale = (string) $request->cookie('bagisto_locale', app()->getLocale());
        $currency = (string) $request->cookie('bagisto_currency', core()->getCurrentCurrencyCode());

        if ($customerId) {
            return sha1('customer|'.$customerId.'|'.$locale.'|'.$currency);
        }

        $sessionCookie = (string) $request->cookie((string) config('session.cookie', 'laravel_session'), '');
        $sessionId = $sessionCookie !== '' ? $sessionCookie : (string) session()->getId();

        return sha1('guest|'.$sessionId.'|'.$locale.'|'.$currency);
    }

    /**
     * Private cart tags for the current request context.
     */
    public static function currentPrivateTags(?Request $request = null): array
    {
        return self::privateTagsForContext(self::currentContextKey($request), self::SCOPES);
    }

    /**
     * Private cart tags for a specific customer.
     */
    public static function customerPrivateTags(int $customerId): array
    {
        return self::privateTagsForContext('customer_'.$customerId, self::SCOPES);
    }

    /**
     * Private cart tags for the guest session currently making the request.
     */
    public static function guestPrivateTags(?Request $request = null): array
    {
        return self::privateTagsForContext(self::guestContextKey($request), self::SCOPES);
    }

    /**
     * Single route-scoped tag for the active context.
     */
    public static function responseTags(string $scope, ?Request $request = null): array
    {
        return self::privateTagsForContext(self::responseContextKey($request), [$scope]);
    }

    /**
     * Single route-scoped tag for an arbitrary private-cache family.
     *
     * The $family parameter is retained for call-site compatibility but no
     * longer produces a tag — one tag per route/scope is emitted instead.
     */
    public static function responseTagsForFamily(string $family, string $scope, ?Request $request = null): array
    {
        return self::privateTagsForContext(self::responseContextKey($request), [$scope]);
    }

    /**
     * Current-context tags for the given private-cache scopes.
     */
    public static function currentPrivateTagsForFamily(string $family, array $scopes, ?Request $request = null): array
    {
        return self::privateTagsForContext(self::currentContextKey($request), $scopes);
    }

    /**
     * Customer-context tags for the given private-cache scopes.
     */
    public static function customerPrivateTagsForFamily(string $family, int $customerId, array $scopes): array
    {
        return self::privateTagsForContext('customer_'.$customerId, $scopes);
    }

    /**
     * Guest-context tags for the given private-cache scopes.
     */
    public static function guestPrivateTagsForFamily(string $family, array $scopes, ?Request $request = null): array
    {
        return self::privateTagsForContext(self::guestContextKey($request), $scopes);
    }

    /**
     * Resolve the tag context used for fresh response writes.
     */
    private static function responseContextKey(?Request $request = null): string
    {
        if ($customerId = auth()->guard('customer')->id()) {
            return 'customer_'.$customerId;
        }

        return 'guest_'.self::privateCookieValue($request);
    }

    /**
     * Resolve the tag context that should be purged for the current request.
     */
    private static function currentContextKey(?Request $request = null): string
    {
        if ($customerId = auth()->guard('customer')->id()) {
            return 'customer_'.$customerId;
        }

        return self::guestContextKey($request);
    }

    /**
     * Resolve the guest tag context from the active private cookie when possible.
     */
    private static function guestContextKey(?Request $request = null): string
    {
        $request ??= request();

        $privateCookie = (string) $request->cookie(self::privateCookieName(), '');

        if ($privateCookie !== '') {
            return 'guest_'.$privateCookie;
        }

        return 'guest_'.self::privateCookieValue($request);
    }

    /**
     * Expand scopes into private cache tags.
     *
     * One tag per route/scope: "{scope}-{contextKey}". The context key already
     * isolates per customer/guest, so a shared family tag is not needed.
     */
    private static function privateTagsForContext(string $contextKey, array $scopes): array
    {
        $tags = [];

        foreach ($scopes as $scope) {
            $tags[] = $scope.'-'.$contextKey;
        }

        return array_values(array_unique(array_filter($tags)));
    }

    /**
     * Build catalog content tags ("product_{id}") for a set of product ids.
     *
     * These reuse the same tag namespace emitted on public product pages, so
     * the existing Product listener purge — LSCache::purgeTags(['product_{id}'])
     * — also evicts any private cart / wishlist / compare entry displaying the
     * product. This keeps those private caches in sync with catalog price /
     * data changes without any extra listener code.
     */
    public static function productTags(array $productIds): array
    {
        $tags = [];

        foreach ($productIds as $id) {
            $id = (int) $id;

            if ($id > 0) {
                $tags['product_'.$id] = 'product_'.$id;
            }
        }

        return array_values($tags);
    }
}
