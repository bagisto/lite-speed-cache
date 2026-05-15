<?php

namespace Webkul\LSC\Listeners;

use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\LSC\Support\CartCacheContext;
use Webkul\LSC\Support\DebuggableLSCache as LSCache;

class CustomerSession
{
    /**
     * After customer session create (login).
     *
     * @return void
     */
    public function afterCreate($customer)
    {
        LSCache::purgePrivateTags(array_merge(
            CartCacheContext::guestPrivateTags(request()),
            CartCacheContext::customerPrivateTags((int) $customer->id)
        ));

        ResponseCache::forget('/api/checkout/cart');
    }

    /**
     * After customer session destroy (logout).
     *
     * @return void
     */
    public function afterDestroy($customerId)
    {
        LSCache::purgePrivateTags(array_merge(
            CartCacheContext::guestPrivateTags(request()),
            $customerId ? CartCacheContext::customerPrivateTags((int) $customerId) : []
        ));

        ResponseCache::forget('/api/checkout/cart');
    }
}
