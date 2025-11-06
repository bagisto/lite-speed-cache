<?php

return [
    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Налаштуйте параметри LSCache для покращення продуктивності.',
                'title' => 'LiteSpeed Cache',

                'configuration' => [
                    'info'  => 'Керуйте застосунком LiteSpeed Cache та пов\'язаними налаштуваннями.',
                    'title' => 'Налаштування',

                    'cache-application' => [
                        'info'             => 'Налаштуйте параметри кеш-застосунку.',
                        'title'            => 'Застосунок кешу',
                        'title-info'       => 'Налаштуйте LiteSpeed Cache: увімкніть/вимкніть кеш, встановіть значення TTL за замовчуванням і виберіть кешування лише для гостей.',
                        'status'           => 'Статус',
                        'default-ttl'      => 'TTL за замовчуванням (час життя)',
                        'default-ttl-info' => 'Встановіть час життя кешованих елементів за замовчуванням у секундах <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Детальніше</a>.',
                        'guest-only'       => 'Лише для гостей',
                        'guest-only-info'  => 'Увімкніть кешування лише для гостей. Якщо вимкнено, кешування буде застосовано до всіх користувачів.',
                    ],
                ],
            ],
        ],
    ],
];
