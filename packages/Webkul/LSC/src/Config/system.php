<?php

return [
    /**
     * LiteSpeedCache Configuration.
     */
    [
        'key'  => 'lsc',
        'name' => 'lsc::app.configuration.index.lsc.title',
        'info' => 'lsc::app.configuration.index.lsc.info',
        'sort' => 1,
    ], [
        'key'  => 'lsc.configuration',
        'name' => 'lsc::app.configuration.index.lsc.title',
        'info' => 'lsc::app.configuration.index.lsc.configuration.info',
        'icon' => 'settings/store.svg',
        'sort' => 1,
    ], [
        'key'    => 'lsc.configuration.cache_application',
        'name'   => 'lsc::app.configuration.index.lsc.configuration.cache-application.title',
        'info'   => 'lsc::app.configuration.index.lsc.configuration.cache-application.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'    => 'active',
                'title'   => 'lsc::app.configuration.index.lsc.configuration.cache-application.status',
                'type'    => 'boolean',
                'default' => false,
            ], [
                'name'       => 'default_ttl',
                'title'      => 'lsc::app.configuration.index.lsc.configuration.cache-application.default-ttl',
                'info'       => 'lsc::app.configuration.index.lsc.configuration.cache-application.default-ttl-info',
                'type'       => 'text',
                'depends'    => 'active:1',
                'validation' => 'required_if:active,1|integer|min:0|max:86400',
                'default'    => 3600,
            ], [
                'name'       => 'guest_only',
                'title'      => 'lsc::app.configuration.index.lsc.configuration.cache-application.guest-only',
                'info'       => 'lsc::app.configuration.index.lsc.configuration.cache-application.guest-only-info',
                'type'       => 'boolean',
                'depends'    => 'active:1',
                'validation' => 'required_if:active,1',
                'default'    => true,
            ],
        ],
    ], [
        'key'    => 'lsc.configuration.esi_settings',
        'name'   => 'lsc::app.configuration.index.lsc.configuration.esi-settings.title',
        'info'   => 'lsc::app.configuration.index.lsc.configuration.esi-settings.title-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'    => 'enabled',
                'title'   => 'lsc::app.configuration.index.lsc.configuration.esi-settings.status',
                'info'    => 'lsc::app.configuration.index.lsc.configuration.esi-settings.status-info',
                'type'    => 'boolean',
                'default' => false,
            ], [
                'name'       => 'private_ttl',
                'title'      => 'lsc::app.configuration.index.lsc.configuration.esi-settings.private-ttl',
                'info'       => 'lsc::app.configuration.index.lsc.configuration.esi-settings.private-ttl-info',
                'type'       => 'text',
                'depends'    => 'enabled:1',
                'validation' => 'required_if:enabled,1|integer|min:0|max:86400',
                'default'    => 3600,
            ], [
                'name'       => 'esi_ttl',
                'title'      => 'lsc::app.configuration.index.lsc.configuration.esi-settings.esi-ttl',
                'info'       => 'lsc::app.configuration.index.lsc.configuration.esi-settings.esi-ttl-info',
                'type'       => 'text',
                'depends'    => 'enabled:1',
                'validation' => 'required_if:enabled,1|integer|min:0|max:86400',
                'default'    => 3600,
            ], [
                'name'       => 'cart_cache_mode',
                'title'      => 'lsc::app.configuration.index.lsc.configuration.esi-settings.cart-cache-mode',
                'info'       => 'lsc::app.configuration.index.lsc.configuration.esi-settings.cart-cache-mode-info',
                'type'       => 'select',
                'depends'    => 'enabled:1',
                'options'    => [
                    [
                        'title' => 'lsc::app.configuration.index.lsc.configuration.esi-settings.mode-no-cache',
                        'value' => 'no-cache',
                    ],
                    [
                        'title' => 'lsc::app.configuration.index.lsc.configuration.esi-settings.mode-private',
                        'value' => 'private',
                    ],
                    [
                        'title' => 'lsc::app.configuration.index.lsc.configuration.esi-settings.mode-esi',
                        'value' => 'esi',
                    ],
                ],
                'default'    => 'no-cache',
            ], [
                'name'    => 'enable_cart_page_cache',
                'title'   => 'lsc::app.configuration.index.lsc.configuration.esi-settings.cart-page-cache',
                'info'    => 'lsc::app.configuration.index.lsc.configuration.esi-settings.cart-page-cache-info',
                'type'    => 'boolean',
                'depends' => 'enabled:1',
                'default' => true,
            ], [
                'name'    => 'enable_mini_cart_esi',
                'title'   => 'lsc::app.configuration.index.lsc.configuration.esi-settings.mini-cart-esi',
                'info'    => 'lsc::app.configuration.index.lsc.configuration.esi-settings.mini-cart-esi-info',
                'type'    => 'boolean',
                'depends' => 'enabled:1',
                'default' => true,
            ],
        ],
    ],
];
