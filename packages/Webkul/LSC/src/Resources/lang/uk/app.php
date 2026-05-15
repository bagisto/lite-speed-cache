<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'Кеш LiteSpeed',
        ],

        'acl' => [
            'title' => 'Кеш LiteSpeed',
            'view'  => 'Перегляд',
            'purge' => 'Очистити',
        ],

        'page' => [
            'title' => 'Кеш LiteSpeed',
            'info'  => 'Переглядайте теги кешу та виконуйте цільові очищення для усунення проблем і обслуговування в продакшені.',
        ],

        'cards' => [
            'quick' => [
                'title' => 'Швидкі дії',
                'info'  => 'Використовуйте швидкі очищення для головної сторінки або, за потреби, для всього кешу.',
            ],

            'debug' => [
                'title'         => 'Заголовки налагодження',
                'tag-label'     => 'Тег:',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => 'Політика:',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => 'Очищення:',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => 'Очистити за категорією',
                'info'  => 'Знайдіть категорію та очистіть лише тег, заснований на її ID.',
            ],

            'product' => [
                'title' => 'Очистити за продуктом',
                'info'  => 'Знайдіть продукт та очистіть лише тег, заснований на його ID.',
            ],

            'tag' => [
                'title' => 'Очистити за тегом',
                'info'  => 'Введіть один або кілька тегів LiteSpeed, розділених комами або пробілами.',
            ],

            'url' => [
                'title' => 'Очистити за URL',
                'info'  => 'Надішліть запит на очищення для точного шляху URL (відносного або абсолютного).',
            ],
        ],

        'actions' => [
            'purge-all'      => 'Очистити весь кеш',
            'purge-home'     => 'Очистити головну сторінку',
            'purge-category' => 'Очистити категорію',
            'purge-product'  => 'Очистити продукт',
            'purge-tag'      => 'Очистити тег',
            'purge-url'      => 'Очистити URL',
        ],

        'badges' => [
            'id-tag'     => 'ID тег',
            'manual'     => 'Вручну',
            'exact-path' => 'Точний шлях',
        ],

        'fields' => [
            'category' => 'Категорія',
            'product'  => 'Продукт',
            'tags'     => 'Теги',
            'url'      => 'URL',
        ],

        'labels' => [
            'target-tag' => 'Цільовий тег:',
        ],

        'placeholders' => [
            'category' => 'Пошук категорій за назвою, slug або ID',
            'product'  => 'Пошук продуктів за назвою',
            'tags'     => 'Приклад: category_5 product_22 home',
            'url'      => 'Приклад: /footwears або https://example.com/footwears',
        ],

        'hints' => [
            'category' => 'Виберіть точну категорію перед відправкою.',
            'product'  => 'Виберіть точний продукт перед відправкою.',
            'tags'     => 'Символ підстановки (*) тут заблокований. Замість цього використовуйте «Очистити весь кеш».',
            'url'      => 'Запит нормалізується до шляху storefront перед відправкою до LiteSpeed.',
        ],

        'confirm' => [
            'all'      => 'Очистити весь кеш LiteSpeed? Це руйнівна дія.',
            'home'     => 'Очистити теги кешу головної сторінки?',
            'category' => 'Очистити тег кешу вибраної категорії?',
            'product'  => 'Очистити тег кешу вибраного продукту?',
            'tag'      => 'Очистити наданий список тегів LiteSpeed?',
            'url'      => 'Очистити наданий шлях URL?',
        ],

        'flash' => [
            'purge-all' => 'Запит на повне очищення кешу LiteSpeed відправлено.',
            'home'      => 'Запит на очищення головної сторінки відправлено.',
            'category'  => 'Запит на очищення відправлено для категорії :name.',
            'product'   => 'Запит на очищення відправлено для продукту :name.',
            'tags'      => 'Запит на очищення тегів відправлено.',
            'url'       => 'Запит на очищення URL відправлено для :url.',
        ],
    ],

    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Налаштуйте параметри LSCache для покращення продуктивності.',
                'title' => 'Кеш LiteSpeed',

                'configuration' => [
                    'info'  => 'Керуйте застосунком LiteSpeed Cache та пов’язаними налаштуваннями.',
                    'title' => 'Конфігурація',

                    'cache-application' => [
                        'info'             => 'Налаштуйте параметри застосунку кешу.',
                        'title'            => 'Застосунок кешу',
                        'title-info'       => 'Налаштуйте LiteSpeed Cache: увімкніть/вимкніть кеш і встановіть стандартний TTL.',
                        'status'           => 'Статус',
                        'default-ttl'      => 'TTL за замовчуванням (час життя)',
                        'default-ttl-info' => 'Встановіть стандартний час життя елементів кешу в секундах <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Детальніше</a>.',
                        'debug-mode'       => 'Режим налагодження',
                        'debug-mode-info'  => 'При увімкненні додає заголовки налагодження, що показують теги LiteSpeed, рішення cache-control і операції очищення для кожного запиту.',
                        'cache-path'       => 'Шлях кешу',
                        'cache-path-info'  => 'Повний шлях до приватного каталогу кешу LiteSpeed. За замовчуванням: /usr/local/lsws/cachedata/priv (Enterprise) або /tmp/lshttpd/lscache (OpenLiteSpeed).',
                    ],
                ],
            ],
        ],
    ],
];
