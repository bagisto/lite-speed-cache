<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'Кэш LiteSpeed',
        ],

        'acl' => [
            'title' => 'Кэш LiteSpeed',
            'view'  => 'Просмотр',
            'purge' => 'Очистить',
        ],

        'page' => [
            'title' => 'Кэш LiteSpeed',
            'info'  => 'Просматривайте теги кэша и выполняйте целевые очистки для устранения неполадок и обслуживания в продакшене.',
        ],

        'cards' => [
            'quick' => [
                'title' => 'Быстрые действия',
                'info'  => 'Используйте быстрые действия очистки для главной страницы или, при необходимости, для всего кэша.',
            ],

            'debug' => [
                'title'         => 'Отладочные заголовки',
                'tag-label'     => 'Тег:',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => 'Политика:',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => 'Очистка:',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => 'Очистка по категории',
                'info'  => 'Найдите категорию и очистите только тег, основанный на её ID.',
            ],

            'product' => [
                'title' => 'Очистка по продукту',
                'info'  => 'Найдите продукт и очистите только тег, основанный на его ID.',
            ],

            'tag' => [
                'title' => 'Очистка по тегу',
                'info'  => 'Введите один или несколько тегов LiteSpeed, разделённых запятыми или пробелами.',
            ],

            'url' => [
                'title' => 'Очистка по URL',
                'info'  => 'Отправьте запрос на очистку для точного пути URL (относительного или абсолютного).',
            ],
        ],

        'actions' => [
            'purge-all'      => 'Очистить весь кэш',
            'purge-home'     => 'Очистить главную страницу',
            'purge-category' => 'Очистить категорию',
            'purge-product'  => 'Очистить продукт',
            'purge-tag'      => 'Очистить тег',
            'purge-url'      => 'Очистить URL',
        ],

        'badges' => [
            'id-tag'     => 'ID тег',
            'manual'     => 'Вручную',
            'exact-path' => 'Точный путь',
        ],

        'fields' => [
            'category' => 'Категория',
            'product'  => 'Продукт',
            'tags'     => 'Теги',
            'url'      => 'URL',
        ],

        'labels' => [
            'target-tag' => 'Целевой тег:',
        ],

        'placeholders' => [
            'category' => 'Поиск категорий по имени, slug или ID',
            'product'  => 'Поиск продуктов по имени',
            'tags'     => 'Пример: category_5 product_22 home',
            'url'      => 'Пример: /footwears или https://example.com/footwears',
        ],

        'hints' => [
            'category' => 'Выберите точную категорию перед отправкой.',
            'product'  => 'Выберите точный продукт перед отправкой.',
            'tags'     => 'Подстановочный знак (*) здесь запрещён. Используйте «Очистить весь кэш» вместо этого.',
            'url'      => 'Запрос нормализуется в путь storefront перед отправкой в LiteSpeed.',
        ],

        'confirm' => [
            'all'      => 'Очистить весь кэш LiteSpeed? Это разрушительное действие.',
            'home'     => 'Очистить теги кэша главной страницы?',
            'category' => 'Очистить тег кэша выбранной категории?',
            'product'  => 'Очистить тег кэша выбранного продукта?',
            'tag'      => 'Очистить указанный список тегов LiteSpeed?',
            'url'      => 'Очистить указанный путь URL?',
        ],

        'flash' => [
            'purge-all' => 'Запрос на полную очистку кэша LiteSpeed отправлен.',
            'home'      => 'Запрос на очистку главной страницы отправлен.',
            'category'  => 'Запрос на очистку отправлен для категории :name.',
            'product'   => 'Запрос на очистку отправлен для продукта :name.',
            'tags'      => 'Запрос на очистку тегов отправлен.',
            'url'       => 'Запрос на очистку URL отправлен для :url.',
        ],
    ],

    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Настройте параметры LSCache для повышения производительности.',
                'title' => 'Кэш LiteSpeed',

                'configuration' => [
                    'info'  => 'Управляйте приложением LiteSpeed Cache и связанными настройками.',
                    'title' => 'Конфигурация',

                    'cache-application' => [
                        'info'             => 'Установите параметры приложения кэша.',
                        'title'            => 'Приложение кэша',
                        'title-info'       => 'Настройте LiteSpeed Cache: включите/отключите кэш и задайте TTL по умолчанию.',
                        'status'           => 'Статус',
                        'default-ttl'      => 'TTL по умолчанию (время жизни)',
                        'default-ttl-info' => 'Установите время жизни кэшированных элементов в секундах <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Подробнее</a>.',
                        'debug-mode'       => 'Режим отладки',
                        'debug-mode-info'  => 'При включении добавляет отладочные заголовки, показывающие теги LiteSpeed, решения cache-control и операции очистки для каждого запроса.',
                        'cache-path'       => 'Путь кэша',
                        'cache-path-info'  => 'Полный путь к приватной директории кэша LiteSpeed. По умолчанию: /usr/local/lsws/cachedata/priv (Enterprise) или /tmp/lshttpd/lscache (OpenLiteSpeed).',
                    ],
                ],
            ],
        ],
    ],
];
