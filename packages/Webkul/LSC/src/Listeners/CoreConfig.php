<?php

namespace Webkul\LSC\Listeners;

use Litespeed\LSCache\LSCache;

class CoreConfig
{
    /**
     * After core configuration update.
     *
     * @return void
     */
    public function afterUpdate()
    {
        LSCache::purgeAll();
    }
}
