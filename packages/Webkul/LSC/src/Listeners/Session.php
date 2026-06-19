<?php

namespace Webkul\LSC\Listeners;

use Webkul\LSC\Support\CartCacheContext;
use Webkul\LSC\Support\DebuggableLSCache as LSCache;

class Session
{
    /**
     * Purge per-context private cache on customer login/logout.
     *
     * @param  \Webkul\Customer\Contracts\Customer|int|null  $customer
     * @return void
     */
    public function session($customer = null)
    {
        $customerId = is_object($customer) ? (int) ($customer->id ?? 0) : (int) $customer;

        // Login/logout flips auth state, so also evict the ESI login fragments
        // and the cart-count badge for both the guest and customer contexts.
        $esiScopes = ['login', 'cart-count'];

        $tags = array_merge(
            CartCacheContext::guestPrivateTags(request()),
            CartCacheContext::guestPrivateTagsForFamily('esi', $esiScopes, request()),
            $customerId > 0 ? CartCacheContext::customerPrivateTags($customerId) : [],
            $customerId > 0 ? CartCacheContext::customerPrivateTagsForFamily('esi', $customerId, $esiScopes) : []
        );

        LSCache::purgePrivateTags($tags);
    }
}
