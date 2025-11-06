<?php

return [
    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Настройте параметры LSCache для повышения производительности.',
                'title' => 'LiteSpeed Cache',

                'configuration' => [
                    'info'  => 'Управляйте приложением LiteSpeed Cache и сопутствующими настройками.',
                    'title' => 'Конфигурация',

                    'cache-application' => [
                        'info'             => 'Установите параметры кэш-приложения.',
                        'title'            => 'Кэш-приложение',
                        'title-info'       => 'Настройте LiteSpeed Cache: включите/отключите кэширование, установите значение TTL по умолчанию и выберите кэширование только для гостей.',
                        'status'           => 'Статус',
                        'default-ttl'      => 'TTL по умолчанию (время жизни)',
                        'default-ttl-info' => 'Установите время жизни кэшированных элементов по умолчанию в секундах <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Подробнее</a>.',
                        'guest-only'       => 'Только для гостей',
                        'guest-only-info'  => 'Включите кэширование только для гостей. Если отключено, кэширование будет применяться ко всем пользователям.',
                    ],
                ],
            ],
        ],
    ],
];
