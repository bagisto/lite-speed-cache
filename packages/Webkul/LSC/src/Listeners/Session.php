<?php

namespace Webkul\LSC\Listeners;

use Webkul\LSC\Support\CartCacheContext;
use Webkul\LSC\Support\DebuggableLSCache as LSCache;

class Session
{
    /**
     * After page update
     *
     * @param  \Webkul\CMS\Contracts\Page  $page
     * @return void
     */
    public function session($customer = null)
    {
        $customerId = is_object($customer) ? (int) ($customer->id ?? 0) : (int) $customer;

        $tags = array_merge(
            CartCacheContext::guestPrivateTags(request()),
            $customerId > 0 ? CartCacheContext::customerPrivateTags($customerId) : []
        );

        LSCache::purgePrivateTags($tags);
    }
}
