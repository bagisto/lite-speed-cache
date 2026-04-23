<?php

namespace Webkul\LSC\Listeners;

use Webkul\LSC\Support\DebuggableLSCache as LSCache;

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
