<?php

return [
    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Configureer LSCache-instellingen voor betere prestaties.',
                'title' => 'LiteSpeed Cache',

                'configuration' => [
                    'info'  => 'Beheer LiteSpeed Cache-applicatie en gerelateerde instellingen.',
                    'title' => 'Configuratie',

                    'cache-application' => [
                        'default-ttl-info' => 'Stel de standaard levensduur (in seconden) in voor gecachte items <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Meer info</a>.',
                        'default-ttl'      => 'Standaard TTL (Time To Live)',
                        'guest-only-info'  => 'Schakel caching alleen voor gastgebruikers in. Als dit is uitgeschakeld, wordt caching toegepast op alle gebruikers.',
                        'guest-only'       => 'Alleen gasten',
                        'info'             => 'Stel opties voor de cache-applicatie in.',
                        'status'           => 'Status',
                        'title-info'       => 'Configureer LiteSpeed Cache: schakel caching in of uit, stel de standaard TTL in en kies caching alleen voor gasten.',
                        'title'            => 'Cache-applicatie',
                    ],
                ],
            ],
        ],
    ],
];
