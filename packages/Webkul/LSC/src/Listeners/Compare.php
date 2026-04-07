<?php

namespace Webkul\LSC\Listeners;

use LSCache;

class Compare
{
    /**
     * After page update
     *
     * @return void
     */
    public function afterUpdate()
    {
        LSCache::purgeTags(['compare']);
    }

    /**
     * Before page delete
     *
     * @return void
     */
    public function beforeDelete()
    {
        LSCache::purgeTags(['compare']);
    }
}
