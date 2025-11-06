<?php

return [
    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'パフォーマンス向上のためにLSCacheの設定を行います。',
                'title' => 'LiteSpeed キャッシュ',

                'configuration' => [
                    'info'  => 'LiteSpeed Cacheアプリケーションおよび関連設定を管理します。',
                    'title' => '設定',

                    'cache-application' => [
                        'info'             => 'キャッシュアプリケーションのオプションを設定します。',
                        'title'            => 'キャッシュアプリケーション',
                        'title-info'       => 'LiteSpeed Cacheを設定します。キャッシュの有効化/無効化、デフォルトTTLの設定、ゲストのみキャッシュの選択を行います。',
                        'status'           => 'ステータス',
                        'default-ttl'      => 'デフォルトTTL（存続時間）',
                        'default-ttl-info' => 'キャッシュ項目のデフォルトの存続時間（秒）を設定します。 <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">詳細</a>.',
                        'guest-only'       => 'ゲストのみ',
                        'guest-only-info'  => 'ゲストユーザーのみキャッシュする場合は有効にします。無効にすると、すべてのユーザーに対してキャッシュが適用されます。',
                    ],
                ],
            ],
        ],
    ],
];
