<?php

return [
    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Configure LSCache settings for improved performance.',
                'title' => 'LiteSpeed Cache',

                'configuration' => [
                    'info'  => 'Manage LiteSpeed Cache application and related settings.',
                    'title' => 'Configuration',

                    'cache-application' => [
                        'info'             => 'Set cache application options.',
                        'title'            => 'Cache Application',
                        'title-info'       => 'Configure LiteSpeed Cache: enable/disable caching, set the default TTL, and choose guest-only caching.',
                        'status'           => 'Status',
                        'default-ttl'      => 'Default TTL (Time To Live)',
                        'default-ttl-info' => 'Set the default time to live for cached items in seconds <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">More Info</a>.',
                        'guest-only'       => 'Guest Only',
                        'guest-only-info'  => 'Enable caching only for guest users. If disabled, caching will be applied to all users.',
                    ],

                    'esi-settings' => [
                        'title'               => 'ESI & Private Cache',
                        'title-info'          => 'Configure Edge Side Includes (ESI) and private cache settings for user-specific content like cart, wishlist, and compare.',
                        'status'              => 'Enable ESI',
                        'status-info'         => 'Enable ESI (Edge Side Includes) for user-specific dynamic content. This allows caching cart-related components per user.',
                        'private-ttl'         => 'Private Cache TTL',
                        'private-ttl-info'    => 'Time-to-live in seconds for private (user-isolated) cache. Applies to cart page, wishlist, and compare. Recommended: 300 (5 minutes).',
                        'esi-ttl'             => 'ESI Component TTL',
                        'esi-ttl-info'        => 'Time-to-live in seconds for ESI components (mini cart, cart count). Recommended: 300 (5 minutes).',
                        'cart-cache-mode'     => 'Cart Cache Mode',
                        'cart-cache-mode-info'=> 'How to handle cart caching: no-cache (disabled), private (user-isolated full page), or esi (ESI components).',
                        'mode-no-cache'       => 'No Cache (Default)',
                        'mode-private'        => 'Private Cache',
                        'mode-esi'            => 'ESI Components',
                        'cart-page-cache'     => 'Enable Cart Page Cache',
                        'cart-page-cache-info'=> 'Enable private caching for the cart page (/checkout/cart). Each user sees their own cached version.',
                        'mini-cart-esi'       => 'Enable Mini Cart ESI',
                        'mini-cart-esi-info'  => 'Load mini cart (header cart) via ESI. The main page stays publicly cached while cart is loaded per-user.',
                    ],
                ],
            ],
        ],
    ],
];
