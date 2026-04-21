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
                        'title-info'       => 'Configure LiteSpeed Cache: enable/disable caching and set the default TTL.',
                        'status'           => 'Status',
                        'default-ttl'      => 'Default TTL (Time To Live)',
                        'default-ttl-info' => 'Set the default time to live for cached items in seconds <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">More Info</a>.',
                        'cache-path'       => 'Cache Path',
                        'cache-path-info'  => 'Full path to LiteSpeed private cache directory. Default: /usr/local/lsws/cachedata/priv (Enterprise) or /tmp/lshttpd/lscache (OpenLiteSpeed).',
                    ],
                ],
            ],
        ],
    ],
];
