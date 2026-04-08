<?php

namespace Webkul\LSC\Listeners;

use Litespeed\LSCache\LSCache;
use Webkul\LSC\Traits\DeletesAllCache;

class ThemeCustomization
{
    use DeletesAllCache;

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
        $this->deletePrivCache();

        LSCache::purgeTags(['home', 'home-header']);
    }
}
