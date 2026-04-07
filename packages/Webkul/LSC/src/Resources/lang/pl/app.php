<?php

return [
    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Skonfiguruj ustawienia LSCache, aby poprawić wydajność.',
                'title' => 'LiteSpeed Cache',

                'configuration' => [
                    'info'  => 'Zarządzaj aplikacją LiteSpeed Cache i powiązanymi ustawieniami.',
                    'title' => 'Konfiguracja',

                    'cache-application' => [
                        'info'             => 'Ustaw opcje aplikacji pamięci podręcznej.',
                        'title'            => 'Aplikacja pamięci podręcznej',
                        'title-info'       => 'Skonfiguruj LiteSpeed Cache: włącz lub wyłącz cachowanie, ustaw domyślny TTL i wybierz cachowanie tylko dla niezalogowanych.',
                        'status'           => 'Status',
                        'default-ttl'      => 'Domyślny TTL (czas życia)',
                        'default-ttl-info' => 'Ustaw domyślny czas życia (w sekundach) dla elementów przechowywanych w pamięci podręcznej <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Więcej informacji</a>.',
                        'guest-only'       => 'Tylko dla niezalogowanych',
                        'guest-only-info'  => 'Włącz cachowanie tylko dla niezalogowanych użytkowników. Jeśli wyłączone, cachowanie będzie stosowane dla wszystkich użytkowników.',
                    ],
                ],
            ],
        ],
    ],
];
