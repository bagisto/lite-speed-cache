<?php

namespace Webkul\LSC\Listeners;

use Litespeed\LSCache\LSCache;
use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\LSC\Traits\DeletesAllCache;

class CustomerSession
{
    use DeletesAllCache;

    /**
     * After customer session create (login).
     *
     * @return void
     */
    public function afterCreate()
    {
        $this->deletePrivCache();

        LSCache::purgeTags(['home', 'home-header']);
        LSCache::purgeItems(['/api/checkout/cart']);

        ResponseCache::forget('/api/checkout/cart');
    }

    /**
     * After customer session destroy (logout).
     *
     * @return void
     */
    public function afterDestroy()
    {
        $this->deletePrivCache();

        LSCache::purgeTags(['home', 'home-header']);
        LSCache::purgeItems(['/api/checkout/cart']);

        ResponseCache::forget('/api/checkout/cart');
    }
}
