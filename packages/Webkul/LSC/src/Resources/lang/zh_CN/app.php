<?php

return [
    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => '配置 LSCache 设置以提高性能。',
                'title' => 'LiteSpeed 缓存',

                'configuration' => [
                    'info'  => '管理 LiteSpeed Cache 应用及相关设置。',
                    'title' => '配置',

                    'cache-application' => [
                        'info'             => '设置缓存应用选项。',
                        'title'            => '缓存应用',
                        'title-info'       => '配置 LiteSpeed 缓存：启用或禁用缓存、设置默认 TTL，并选择是否仅为访客启用缓存。',
                        'status'           => '状态',
                        'default-ttl'      => '默认 TTL（生存时间）',
                        'default-ttl-info' => '以秒为单位设置缓存项的默认生存时间 <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">更多信息</a>。',
                        'guest-only'       => '仅访客',
                        'guest-only-info'  => '仅为访客启用缓存。若禁用，缓存将应用于所有用户。',
                    ],
                ],
            ],
        ],
    ],
];
