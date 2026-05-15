<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'LiteSpeed 缓存',
        ],

        'acl' => [
            'title' => 'LiteSpeed 缓存',
            'view'  => '查看',
            'purge' => '清除',
        ],

        'page' => [
            'title' => 'LiteSpeed 缓存',
            'info'  => '查看缓存标签，并执行定向清除操作，以便在生产环境中进行故障排查和维护。',
        ],

        'cards' => [
            'quick' => [
                'title' => '快速操作',
                'info'  => '对首页执行快速清除，或在必要时清除整个缓存。',
            ],

            'debug' => [
                'title'         => '调试头信息',
                'tag-label'     => '标签:',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => '策略:',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => '清除:',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => '按分类清除',
                'info'  => '搜索分类，并仅清除基于其 ID 的标签。',
            ],

            'product' => [
                'title' => '按商品清除',
                'info'  => '搜索商品，并仅清除基于其 ID 的标签。',
            ],

            'tag' => [
                'title' => '按标签清除',
                'info'  => '输入一个或多个 LiteSpeed 标签，用逗号或空格分隔。',
            ],

            'url' => [
                'title' => '按 URL 清除',
                'info'  => '对相对路径或完整 URL 发送精确的路径清除请求。',
            ],
        ],

        'actions' => [
            'purge-all'      => '清除全部缓存',
            'purge-home'     => '清除首页缓存',
            'purge-category' => '清除分类缓存',
            'purge-product'  => '清除商品缓存',
            'purge-tag'      => '清除标签缓存',
            'purge-url'      => '清除 URL 缓存',
        ],

        'badges' => [
            'id-tag'     => 'ID 标签',
            'manual'     => '手动',
            'exact-path' => '精确路径',
        ],

        'fields' => [
            'category' => '分类',
            'product'  => '商品',
            'tags'     => '标签',
            'url'      => 'URL',
        ],

        'labels' => [
            'target-tag' => '目标标签:',
        ],

        'placeholders' => [
            'category' => '通过名称、slug 或 ID 搜索分类',
            'product'  => '通过名称搜索商品',
            'tags'     => '示例: category_5 product_22 home',
            'url'      => '示例: /footwears 或 https://example.com/footwears',
        ],

        'hints' => [
            'category' => '提交前请选择一个准确的分类。',
            'product'  => '提交前请选择一个准确的商品。',
            'tags'     => '此处禁止使用通配符 (*)。请改用“清除全部缓存”。',
            'url'      => '请求在发送到 LiteSpeed 之前会被规范化为 storefront 路径。',
        ],

        'confirm' => [
            'all'      => '确定要清除全部 LiteSpeed 缓存吗？此操作不可恢复。',
            'home'     => '确定要清除首页缓存标签吗？',
            'category' => '确定要清除所选分类的缓存标签吗？',
            'product'  => '确定要清除所选商品的缓存标签吗？',
            'tag'      => '确定要清除提供的 LiteSpeed 标签列表吗？',
            'url'      => '确定要清除提供的 URL 路径吗？',
        ],

        'flash' => [
            'purge-all' => '已发送清除全部 LiteSpeed 缓存的请求。',
            'home'      => '已发送清除首页缓存的请求。',
            'category'  => '已发送分类 :name 的缓存清除请求。',
            'product'   => '已发送商品 :name 的缓存清除请求。',
            'tags'      => '已发送标签清除请求。',
            'url'       => '已发送 URL :url 的缓存清除请求。',
        ],
    ],

    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => '配置 LSCache 设置以提升性能。',
                'title' => 'LiteSpeed 缓存',

                'configuration' => [
                    'info'  => '管理 LiteSpeed Cache 应用及相关设置。',
                    'title' => '配置',

                    'cache-application' => [
                        'info'             => '设置缓存应用选项。',
                        'title'            => '缓存应用',
                        'title-info'       => '配置 LiteSpeed Cache：启用/禁用缓存并设置默认 TTL。',
                        'status'           => '状态',
                        'default-ttl'      => '默认 TTL（生存时间）',
                        'default-ttl-info' => '设置缓存项的默认生存时间（秒）<a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">更多信息</a>。',
                        'debug-mode'       => '调试模式',
                        'debug-mode-info'  => '启用后，将添加调试头信息，显示 LiteSpeed 标签、cache-control 决策以及请求级别的清除操作。',
                        'cache-path'       => '缓存路径',
                        'cache-path-info'  => 'LiteSpeed 私有缓存目录的完整路径。默认：/usr/local/lsws/cachedata/priv（Enterprise）或 /tmp/lshttpd/lscache（OpenLiteSpeed）。',
                    ],
                ],
            ],
        ],
    ],
];
