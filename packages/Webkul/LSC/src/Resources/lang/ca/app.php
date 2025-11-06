<?php

return [
    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Configura les opcions de LSCache per millorar el rendiment.',
                'title' => 'LiteSpeed Cache',

                'configuration' => [
                    'info'  => 'Gestiona l\'aplicació LiteSpeed Cache i la configuració relacionada.',
                    'title' => 'Configuració',

                    'cache-application' => [
                        'info'             => 'Configura les opcions de l\'aplicació de memòria cau.',
                        'title'            => 'Aplicació de memòria cau',
                        'title-info'       => 'Configura LiteSpeed Cache: habilita/deshabilita la memòria cau, estableix el TTL predeterminat i tria l\'opció només per a convidats.',
                        'status'           => 'Estat',
                        'default-ttl'      => 'TTL predeterminat (Temps de vida)',
                        'default-ttl-info' => 'Estableix el temps de vida predeterminat per als elements en memòria cau en segons <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Més informació</a>.',
                        'guest-only'       => 'Només per a convidats',
                        'guest-only-info'  => 'Habilita la memòria cau només per a usuaris convidats. Si està desactivat, la memòria cau s\'aplicarà a tots els usuaris.',
                    ],
                ],
            ],
        ],
    ],
];
