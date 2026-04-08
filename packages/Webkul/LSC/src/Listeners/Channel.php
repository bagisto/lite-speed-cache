<?php

namespace Webkul\LSC\Listeners;

use Litespeed\LSCache\LSCache;

class Channel
{
    /**
     * After channel update.
     *
     * @param  \Webkul\Core\Contracts\Channel  $channel
     * @return void
     */
    public function afterUpdate($channel)
    {
        LSCache::purgeAll();
    }
}
