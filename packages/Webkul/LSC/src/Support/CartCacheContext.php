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
     * Route-specific tags plus the shared cart tags for the active context.
     */
    public static function responseTags(string $scope, ?Request $request = null): array
    {
        $contextKey = self::responseContextKey($request);

        return self::privateTagsForContext($contextKey, ['cart-private', $scope]);
    }

    /**
     * Build private tags for an arbitrary private-cache family and scope.
     */
    public static function responseTagsForFamily(string $family, string $scope, ?Request $request = null): array
    {
        $contextKey = self::responseContextKey($request);

        return self::privateTagsForContext($contextKey, [$family, $scope]);
    }

    /**
     * Current-context tags for an arbitrary private-cache family and scopes.
     */
    public static function currentPrivateTagsForFamily(string $family, array $scopes, ?Request $request = null): array
    {
        $contextKey = self::currentContextKey($request);

        return self::privateTagsForContext($contextKey, array_merge([$family], $scopes));
    }

    /**
     * Customer-context tags for an arbitrary private-cache family and scopes.
     */
    public static function customerPrivateTagsForFamily(string $family, int $customerId, array $scopes): array
    {
        return self::privateTagsForContext('customer_'.$customerId, array_merge([$family], $scopes));
    }

    /**
     * Guest-context tags for an arbitrary private-cache family and scopes.
     */
    public static function guestPrivateTagsForFamily(string $family, array $scopes, ?Request $request = null): array
    {
        return self::privateTagsForContext(self::guestContextKey($request), array_merge([$family], $scopes));
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
     */
    private static function privateTagsForContext(string $contextKey, array $scopes): array
    {
        $tags = [];

        foreach ($scopes as $scope) {
            $tags[] = $scope;
            $tags[] = $scope.'-'.$contextKey;
        }

        return array_values(array_unique(array_filter($tags)));
    }
}
