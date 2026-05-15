<?php

namespace Webkul\LSC\Listeners;

use Webkul\LSC\Support\CartCacheContext;
use Webkul\LSC\Support\DebuggableLSCache as LSCache;

class ThemeCustomization
{
    /**
     * After theme customization create
     *
     * @return void
     */
    public function afterCreate($theme)
    {
        if ($theme->type == 'footer_links') {
            LSCache::purgeAll();
        } else {
            LSCache::purgeTags(['home']);
        }
    }

    /**
     * After theme customization update
     *
     * @return void
     */
    public function afterUpdate($theme)
    {
        if ($theme->type == 'footer_links') {
            LSCache::purgeAll();
        } else {
            LSCache::purgeTags(['home']);
        }
    }

    /**
     * Before theme customization delete
     *
     * @return void
     */
    public function beforeDelete()
    {
        LSCache::purgeTags(['home']);
    }

    /**
     * After cart change (add, update)
     *
     * @return void
     */
    public function afterChange()
    {
        LSCache::purgePrivateTags(CartCacheContext::currentPrivateTags(request()));
    }
}
