<?php

namespace Webkul\LSC\Support;

use Litespeed\LSCache\LSCache as BaseLSCache;

class DebuggableLSCache
{
    /**
     * Proxy tag purges while recording them for debug headers.
     */
    public static function purgeTags(array|string $tags, ...$arguments)
    {
        LiteSpeedDebug::recordPurgeTags($tags);

        return BaseLSCache::purgeTags($tags, ...$arguments);
    }

    /**
     * Proxy global purges while recording them for debug headers.
     */
    public static function purgeAll(...$arguments)
    {
        LiteSpeedDebug::recordPurgeAll();

        return BaseLSCache::purgeAll(...$arguments);
    }

    /**
     * Proxy item purges while recording them for debug headers.
     */
    public static function purgeItems(array|string $items, ...$arguments)
    {
        LiteSpeedDebug::recordPurgeUrls((array) $items);

        return BaseLSCache::purgeItems((array) $items, ...$arguments);
    }

    /**
     * Purge one or more private tags.
     */
    public static function purgePrivateTags(array|string $tags, bool $stale = true)
    {
        LiteSpeedDebug::recordPurgePrivateTags((array) $tags);

        $privateTagString = implode(',', array_map(
            static fn (string $tag): string => 'tag='.$tag,
            array_values(array_filter((array) $tags))
        ));

        return BaseLSCache::purge('private, '.$privateTagString, $stale);
    }
}
