<?php

namespace Webkul\LSC\Listeners;

use Litespeed\LSCache\LSCache;

class Refund extends Product
{
    /**
     * After refund is created
     *
     * @param  \Webkul\Sale\Contracts\Refund  $refund
     * @return void
     */
    public function afterCreate($refund)
    {
        foreach ($refund->items as $item) {
            if (! $item->product) {
                continue;
            }

            $urls = $this->getForgettableUrls($item->product);

            LSCache::purgeTags($urls);
        }
    }
}
