<?php

namespace Webkul\LSC\Listeners;

use LSCache;
use Webkul\LSC\Traits\UserCacheVariation;

/**
 * Cart Cache Listener
 * 
 * Handles cache purging when cart operations occur.
 * Ensures ESI cart components are refreshed when cart changes.
 */
class CartCache
{
    use UserCacheVariation;

    /**
     * After item is added to cart.
     *
     * @param  mixed  $cartItem
     * @return void
     */
    public function afterAddToCart($cartItem)
    {
        $this->purgeCartCache();
    }

    /**
     * After item is removed from cart.
     *
     * @param  mixed  $cartItem
     * @return void
     */
    public function afterRemoveFromCart($cartItem)
    {
        $this->purgeCartCache();
    }

    /**
     * After cart item quantity is updated.
     *
     * @param  mixed  $cartItem
     * @return void
     */
    public function afterUpdateCart($cartItem)
    {
        $this->purgeCartCache();
    }

    /**
     * After cart is cleared/emptied.
     *
     * @return void
     */
    public function afterEmptyCart()
    {
        $this->purgeCartCache();
    }

    /**
     * After coupon is applied.
     *
     * @return void
     */
    public function afterApplyCoupon()
    {
        $this->purgeCartCache();
    }

    /**
     * After coupon is removed.
     *
     * @return void
     */
    public function afterRemoveCoupon()
    {
        $this->purgeCartCache();
    }

    /**
     * Purge all cart-related cache.
     *
     * @return void
     */
    protected function purgeCartCache(): void
    {
        // Only purge if ESI caching is enabled
        if (! $this->isEsiEnabled()) {
            return;
        }

        $userTag = $this->getUserCacheTag();

        // Purge ESI cart routes
        LSCache::purgeItems([
            '/esi/mini-cart',
            '/esi/cart-count',
            '/esi/cart-data',
            '/esi/cart-page',
            '/api/esi/mini-cart',
            '/api/esi/cart-count',
            '/api/esi/cart-data',
            '/api/esi/cart-page',
            '/api/checkout/cart',
            '/checkout/cart',
        ]);

        // Purge user-specific cache tags
        LSCache::purgeTags([
            'cart',
            'cart-page',
            'mini-cart',
            'cart-count',
            'cart-data',
            $userTag,
        ]);
    }
}
