<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'LiteSpeed Cache',
        ],

        'acl' => [
            'title' => 'LiteSpeed Cache',
            'view'  => 'View',
            'purge' => 'Purge',
        ],

        'page' => [
            'title' => 'LiteSpeed Cache',
            'info'  => 'Review cache tags and trigger scoped purges for production troubleshooting and maintenance.',
        ],

        'cards' => [
            'quick' => [
                'title' => 'Quick Actions',
                'info'  => 'Use fast purge actions for the homepage or, when absolutely necessary, the entire cache.',
            ],

            'debug' => [
                'title'         => 'Debug Headers',
                'tag-label'     => 'Tag:',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => 'Policy:',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => 'Purge:',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => 'Purge By Category',
                'info'  => 'Search for a category and purge only its ID-based tag.',
            ],

            'product' => [
                'title' => 'Purge By Product',
                'info'  => 'Search for a product and purge only its ID-based tag.',
            ],

            'tag' => [
                'title' => 'Purge By Tag',
                'info'  => 'Enter one or more explicit LiteSpeed tags separated by commas or whitespace.',
            ],

            'url' => [
                'title' => 'Purge By URL',
                'info'  => 'Send an exact URL path purge for a relative path or absolute storefront URL.',
            ],
        ],

        'actions' => [
            'purge-all'      => 'Purge All Cache',
            'purge-home'     => 'Purge Home Page',
            'purge-category' => 'Purge Category',
            'purge-product'  => 'Purge Product',
            'purge-tag'      => 'Purge Tag',
            'purge-url'      => 'Purge URL',
        ],

        'badges' => [
            'id-tag'     => 'ID Tag',
            'manual'     => 'Manual',
            'exact-path' => 'Exact Path',
        ],

        'fields' => [
            'category' => 'Category',
            'product'  => 'Product',
            'tags'     => 'Tags',
            'url'      => 'URL',
        ],

        'labels' => [
            'target-tag' => 'Target tag:',
        ],

        'placeholders' => [
            'category' => 'Search categories by name, slug, or ID',
            'product'  => 'Search products by name',
            'tags'     => 'Example: category_5 product_22 home',
            'url'      => 'Example: /footwears or https://example.com/footwears',
        ],

        'hints' => [
            'category' => 'Choose a concrete category match before submitting.',
            'product'  => 'Choose a concrete product match before submitting.',
            'tags'     => 'Wildcard (*) is intentionally blocked here. Use Purge All instead.',
            'url'      => 'The request is normalized to a storefront path before LiteSpeed receives it.',
        ],

        'confirm' => [
            'all'      => 'Purge the entire LiteSpeed cache? This is a destructive action.',
            'home'     => 'Purge the home page cache tags?',
            'category' => 'Purge the selected category cache tag?',
            'product'  => 'Purge the selected product cache tag?',
            'tag'      => 'Purge the provided LiteSpeed tag list?',
            'url'      => 'Purge the provided URL path?',
        ],

        'flash' => [
            'purge-all' => 'LiteSpeed cache purge-all request sent.',
            'home'      => 'LiteSpeed home page purge request sent.',
            'category'  => 'LiteSpeed purge request sent for category :name.',
            'product'   => 'LiteSpeed purge request sent for product :name.',
            'tags'      => 'LiteSpeed tag purge request sent.',
            'url'       => 'LiteSpeed URL purge request sent for :url.',
        ],
    ],

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
                        'debug-mode'       => 'Debug Mode',
                        'debug-mode-info'  => 'When enabled, append lightweight debug headers that expose LiteSpeed tags, cache-control decisions, and request-scoped purge operations for inspection.',
                        'cache-path'       => 'Cache Path',
                        'cache-path-info'  => 'Full path to LiteSpeed private cache directory. Default: /usr/local/lsws/cachedata/priv (Enterprise) or /tmp/lshttpd/lscache (OpenLiteSpeed).',
                    ],
                ],
            ],
        ],
    ],
];
