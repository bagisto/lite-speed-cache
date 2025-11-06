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
        'catalog.product.update.after'  => [
            'Webkul\LSC\Listeners\Product@afterUpdate',
        ],

        'catalog.product.delete.before' => [
            'Webkul\LSC\Listeners\Product@beforeDelete',
        ],

        'catalog.category.update.after' => [
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

        'checkout.order.save.after'     => [
            'Webkul\LSC\Listeners\Order@afterCancelOrCreate',
        ],

        'sales.order.cancel.after'      => [
            'Webkul\LSC\Listeners\Order@afterCancelOrCreate',
        ],

        'sales.refund.save.after'       => [
            'Webkul\LSC\Listeners\Refund@afterCreate',
        ],

        'cms.page.update.after' => [
            'Webkul\LSC\Listeners\Page@afterUpdate',
        ],

        'cms.page.delete.before' => [
            'Webkul\LSC\Listeners\Page@beforeDelete',
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

        'checkout.cart.add.before' => [
            'Webkul\LSC\Listeners\ThemeCustomization@afterChange',
        ],

        'checkout.cart.update.after' => [
            'Webkul\LSC\Listeners\ThemeCustomization@afterChange',
        ],

        'checkout.cart.delete.before' => [
            'Webkul\LSC\Listeners\ThemeCustomization@afterChange',
        ],

        'customer.after.login' => [
            'Webkul\LSC\Listeners\CustomerSession@afterCreate',
        ],
    ];
}
