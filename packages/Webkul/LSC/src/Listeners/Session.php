<?php

namespace Webkul\LSC\Listeners;

use Litespeed\LSCache\LSCache;

class Session
{
    /**
     * After page update
     *
     * @param  \Webkul\CMS\Contracts\Page  $page
     * @return void
     */
    public function session()
    {
        LSCache::purgeAll();
    }
}
