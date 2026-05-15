<?php

namespace Webkul\LSC\Support;

class LiteSpeedDebug
{
    /**
     * Tags assigned to the current response.
     */
    protected static array $responseTags = [];

    /**
     * Cache control applied to the current response.
     */
    protected static ?string $cacheControl = null;

    /**
     * Purge operations triggered during the current request.
     */
    protected static array $purges = [];

    /**
     * Determine if debug mode is enabled.
     */
    public static function enabled(): bool
    {
        return (bool) env('LSCACHE_DEBUG', false)
            || (bool) core()->getConfigData('lsc.configuration.cache_application.debug_mode');
    }

    /**
     * Remember response cache metadata for later header injection.
     */
    public static function rememberResponse(array $tags = [], ?string $cacheControl = null): void
    {
        if (! self::enabled()) {
            return;
        }

        if ($tags !== []) {
            self::$responseTags = array_values(array_unique(array_merge(
                self::$responseTags,
                array_values(array_filter($tags))
            )));
        }

        if ($cacheControl !== null) {
            self::$cacheControl = $cacheControl;
        }
    }

    /**
     * Track a tag-based purge.
     */
    public static function recordPurgeTags(array|string $tags): void
    {
        if (! self::enabled()) {
            return;
        }

        foreach ((array) $tags as $tag) {
            if (! empty($tag)) {
                self::$purges[] = 'tag='.$tag;
            }
        }

        self::$purges = array_values(array_unique(self::$purges));
    }

    /**
     * Track a private tag-based purge.
     */
    public static function recordPurgePrivateTags(array|string $tags): void
    {
        if (! self::enabled()) {
            return;
        }

        foreach ((array) $tags as $tag) {
            if (! empty($tag)) {
                self::$purges[] = 'private-tag='.$tag;
            }
        }

        self::$purges = array_values(array_unique(self::$purges));
    }

    /**
     * Track exact URL purges when the response sends them explicitly.
     */
    public static function recordPurgeUrls(array|string $urls): void
    {
        if (! self::enabled()) {
            return;
        }

        foreach ((array) $urls as $url) {
            if (! empty($url)) {
                self::$purges[] = 'url='.$url;
            }
        }

        self::$purges = array_values(array_unique(self::$purges));
    }

    /**
     * Track a global purge.
     */
    public static function recordPurgeAll(): void
    {
        if (! self::enabled()) {
            return;
        }

        self::$purges[] = 'all=*';
        self::$purges = array_values(array_unique(self::$purges));
    }

    /**
     * Append debug headers to the outgoing response.
     */
    public static function attachToResponse($response, array $tags = [], ?string $cacheControl = null)
    {
        if (! self::enabled()) {
            return $response;
        }

        self::rememberResponse($tags, $cacheControl);

        if (self::$responseTags !== []) {
            $response->headers->set('X-DEBUG-LiteSpeed-Tag', implode(',', self::$responseTags));
        }

        if (self::$cacheControl !== null) {
            $response->headers->set('X-DEBUG-LiteSpeed-CacheControl', self::$cacheControl);
        }

        if (self::$purges !== []) {
            $response->headers->set('X-DEBUG-LiteSpeed-Purge', implode(',', self::$purges));
        }

        return $response;
    }
}
