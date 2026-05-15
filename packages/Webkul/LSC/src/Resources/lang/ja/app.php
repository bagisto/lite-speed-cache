<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'LiteSpeedキャッシュ',
        ],

        'acl' => [
            'title' => 'LiteSpeedキャッシュ',
            'view'  => '表示',
            'purge' => 'パージ',
        ],

        'page' => [
            'title' => 'LiteSpeedキャッシュ',
            'info'  => 'キャッシュタグを確認し、本番環境でのトラブルシューティングやメンテナンスのためにスコープ指定のパージを実行します。',
        ],

        'cards' => [
            'quick' => [
                'title' => 'クイックアクション',
                'info'  => 'ホームページの高速パージ、または必要に応じてキャッシュ全体のパージを実行します。',
            ],

            'debug' => [
                'title'         => 'デバッグヘッダー',
                'tag-label'     => 'タグ:',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => 'ポリシー:',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => 'パージ:',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => 'カテゴリ別にパージ',
                'info'  => 'カテゴリを検索し、そのIDに基づくタグのみをパージします。',
            ],

            'product' => [
                'title' => '商品別にパージ',
                'info'  => '商品を検索し、そのIDに基づくタグのみをパージします。',
            ],

            'tag' => [
                'title' => 'タグ別にパージ',
                'info'  => 'カンマまたはスペースで区切って、1つ以上のLiteSpeedタグを入力してください。',
            ],

            'url' => [
                'title' => 'URL別にパージ',
                'info'  => '相対パスまたは絶対URLに対して正確なURLパスのパージリクエストを送信します。',
            ],
        ],

        'actions' => [
            'purge-all'      => 'すべてのキャッシュをパージ',
            'purge-home'     => 'ホームページをパージ',
            'purge-category' => 'カテゴリをパージ',
            'purge-product'  => '商品をパージ',
            'purge-tag'      => 'タグをパージ',
            'purge-url'      => 'URLをパージ',
        ],

        'badges' => [
            'id-tag'     => 'IDタグ',
            'manual'     => '手動',
            'exact-path' => '正確なパス',
        ],

        'fields' => [
            'category' => 'カテゴリ',
            'product'  => '商品',
            'tags'     => 'タグ',
            'url'      => 'URL',
        ],

        'labels' => [
            'target-tag' => '対象タグ:',
        ],

        'placeholders' => [
            'category' => '名前、スラッグ、またはIDでカテゴリを検索',
            'product'  => '名前で商品を検索',
            'tags'     => '例: category_5 product_22 home',
            'url'      => '例: /footwears または https://example.com/footwears',
        ],

        'hints' => [
            'category' => '送信前に正確なカテゴリを選択してください。',
            'product'  => '送信前に正確な商品を選択してください。',
            'tags'     => 'ワイルドカード（*）はここでは使用できません。代わりに「すべてのキャッシュをパージ」を使用してください。',
            'url'      => 'リクエストはLiteSpeedに送信される前にストアフロントのパスに正規化されます。',
        ],

        'confirm' => [
            'all'      => 'LiteSpeedキャッシュ全体をパージしますか？この操作は元に戻せません。',
            'home'     => 'ホームページのキャッシュタグをパージしますか？',
            'category' => '選択したカテゴリのキャッシュタグをパージしますか？',
            'product'  => '選択した商品のキャッシュタグをパージしますか？',
            'tag'      => '指定したLiteSpeedタグ一覧をパージしますか？',
            'url'      => '指定したURLパスをパージしますか？',
        ],

        'flash' => [
            'purge-all' => 'LiteSpeedキャッシュの全体パージリクエストが送信されました。',
            'home'      => 'ホームページのパージリクエストが送信されました。',
            'category'  => 'カテゴリ :name のパージリクエストが送信されました。',
            'product'   => '商品 :name のパージリクエストが送信されました。',
            'tags'      => 'タグのパージリクエストが送信されました。',
            'url'       => 'URL :url のパージリクエストが送信されました。',
        ],
    ],

    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'パフォーマンス向上のためにLSCacheの設定を構成します。',
                'title' => 'LiteSpeedキャッシュ',

                'configuration' => [
                    'info'  => 'LiteSpeed Cacheアプリケーションおよび関連設定を管理します。',
                    'title' => '設定',

                    'cache-application' => [
                        'info'             => 'キャッシュアプリケーションのオプションを設定します。',
                        'title'            => 'キャッシュアプリケーション',
                        'title-info'       => 'LiteSpeed Cacheを設定します：キャッシュの有効化/無効化、およびデフォルトTTLの設定。',
                        'status'           => 'ステータス',
                        'default-ttl'      => 'デフォルトTTL（有効期間）',
                        'default-ttl-info' => 'キャッシュアイテムのデフォルト有効期間（秒）を設定します <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">詳細はこちら</a>。',
                        'debug-mode'       => 'デバッグモード',
                        'debug-mode-info'  => '有効にすると、LiteSpeedタグ、cache-controlの判断、およびリクエストごとのパージ操作を表示するデバッグヘッダーを追加します。',
                        'cache-path'       => 'キャッシュパス',
                        'cache-path-info'  => 'LiteSpeedのプライベートキャッシュディレクトリへのフルパス。デフォルト: /usr/local/lsws/cachedata/priv（Enterprise）または /tmp/lshttpd/lscache（OpenLiteSpeed）。',
                    ],
                ],
            ],
        ],
    ],
];
