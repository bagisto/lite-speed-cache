<?php

namespace Webkul\LSC\Listeners;

use Webkul\LSC\Support\CartCacheContext;
use Webkul\LSC\Support\DebuggableLSCache as LSCache;

class Wishlist
{
    /**
     * Purge private wishlist cache for the current customer context.
     */
    public function afterChange(): void
    {
        LSCache::purgePrivateTags(
            CartCacheContext::currentPrivateTagsForFamily('wishlist-private', ['wishlist-api', 'wishlist-page'], request())
        );
    }
}
