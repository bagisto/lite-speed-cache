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
                        'default-ttl-info' => 'Set the default time to live for cached items in seconds <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">More Info</a>.',
                        'default-ttl'      => 'Default TTL (Time To Live)',
                        'guest-only-info'  => 'Enable caching only for guest users. If disabled, caching will be applied to all users.',
                        'guest-only'       => 'Guest Only',
                        'info'             => 'Set cache application options.',
                        'status'           => 'Status',
                        'title-info'       => 'Configure LiteSpeed Cache: enable/disable caching, set the default TTL, and choose guest-only caching.',
                        'title'            => 'Cache Application',
                    ],
                ],
            ],
        ],
    ],
];
