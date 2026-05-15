<?php

namespace Webkul\LSC\Listeners;

use Webkul\LSC\Support\CartCacheContext;
use Webkul\LSC\Support\DebuggableLSCache as LSCache;

class Compare
{
    /**
     * After page update
     *
     * @return void
     */
    public function afterUpdate()
    {
        LSCache::purgePrivateTags(
            CartCacheContext::currentPrivateTagsForFamily('compare-private', ['compare-api', 'compare-page'], request())
        );
    }

    /**
     * Before page delete
     *
     * @return void
     */
    public function beforeDelete()
    {
        LSCache::purgePrivateTags(
            CartCacheContext::currentPrivateTagsForFamily('compare-private', ['compare-api', 'compare-page'], request())
        );
    }
}
