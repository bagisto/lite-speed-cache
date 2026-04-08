<?php

namespace Webkul\LSC\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'catalog.product.create.after'  => [
            'Webkul\LSC\Listeners\Product@afterCreate',
        ],

        'catalog.product.update.after'  => [
            'Webkul\LSC\Listeners\Product@afterUpdate',
        ],
        'customer.wishlist.create.after' => [
            'Webkul\LSC\Listeners\Product@afterCreate',
        ],

        'catalog.product.delete.before' => [
            'Webkul\LSC\Listeners\Product@beforeDelete',
        ],

        'catalog.category.create.after' => [
            'Webkul\LSC\Listeners\Category@afterUpdate',
        ],

        'catalog.category.update.after' => [
            'Webkul\LSC\Listeners\Category@afterUpdate',
        ],

        'catalog.categories.mass-update.after' => [
            'Webkul\LSC\Listeners\Category@afterUpdate',
        ],

        'catalog.category.delete.before' => [
            'Webkul\LSC\Listeners\Category@beforeDelete',
        ],

        'customer.review.update.after' => [
            'Webkul\LSC\Listeners\Review@afterUpdate',
        ],

        'customer.review.delete.before' => [
            'Webkul\LSC\Listeners\Review@beforeDelete',
        ],

        'checkout.order.save.after' => [
            'Webkul\LSC\Listeners\Order@afterCancelOrCreate',
        ],

        'sales.order.cancel.after' => [
            'Webkul\LSC\Listeners\Order@afterCancelOrCreate',
        ],

        'sales.refund.save.after' => [
            'Webkul\LSC\Listeners\Refund@afterCreate',
        ],

        'cms.page.update.after' => [
            'Webkul\LSC\Listeners\Page@afterUpdate',
        ],

        'cms.page.delete.before' => [
            'Webkul\LSC\Listeners\Page@beforeDelete',
        ],

        'customer.compare.create.after' => [
            'Webkul\LSC\Listeners\Compare@afterUpdate',
        ],

        'customer.compare.delete.before' => [
            'Webkul\LSC\Listeners\Compare@beforeDelete',
        ],

        'customer.compare.delete-all.before' => [
            'Webkul\LSC\Listeners\Compare@beforeDelete',
        ],

        'customer.after.login' => [
            'Webkul\LSC\Listeners\Session@session',
            'Webkul\LSC\Listeners\CustomerSession@afterCreate',
        ],

        'customer.after.logout' => [
            'Webkul\LSC\Listeners\Session@session',
            'Webkul\LSC\Listeners\CustomerSession@afterDestroy',
        ],

        'theme_customization.create.after' => [
            'Webkul\LSC\Listeners\ThemeCustomization@afterCreate',
        ],

        'theme_customization.update.after' => [
            'Webkul\LSC\Listeners\ThemeCustomization@afterUpdate',
        ],

        'theme_customization.delete.before' => [
            'Webkul\LSC\Listeners\ThemeCustomization@beforeDelete',
        ],

        'core.channel.update.after' => [
            'Webkul\LSC\Listeners\Channel@afterUpdate',
        ],

        'core.configuration.save.after' => [
            'Webkul\LSC\Listeners\CoreConfig@afterUpdate',
        ],

        'marketing.search_seo.url_rewrites.update.after' => [
            'Webkul\LSC\Listeners\URLRewrite@afterUpdate',
        ],

        'marketing.search_seo.url_rewrites.delete.before' => [
            'Webkul\LSC\Listeners\URLRewrite@beforeDelete',
        ],

        // Cart cache events - for ESI cart caching
        'checkout.cart.add.before' => [
            'Webkul\LSC\Listeners\ThemeCustomization@afterChange',
        ],

        'checkout.cart.add.after' => [
            'Webkul\LSC\Listeners\CartCache@afterAddToCart',
        ],

        'checkout.cart.update.after' => [
            'Webkul\LSC\Listeners\ThemeCustomization@afterChange',
            'Webkul\LSC\Listeners\CartCache@afterUpdateCart',
        ],

        'checkout.cart.delete.before' => [
            'Webkul\LSC\Listeners\ThemeCustomization@afterChange',
        ],

        'checkout.cart.delete.after' => [
            'Webkul\LSC\Listeners\CartCache@afterRemoveFromCart',
        ],

        'checkout.cart.collect.totals.after' => [
            'Webkul\LSC\Listeners\CartCache@afterUpdateCart',
        ],

        // Coupon events
        'checkout.cart.coupon.apply.after' => [
            'Webkul\LSC\Listeners\CartCache@afterApplyCoupon',
        ],

        'checkout.cart.coupon.remove.after' => [
            'Webkul\LSC\Listeners\CartCache@afterRemoveCoupon',
        ],
    ];
}
