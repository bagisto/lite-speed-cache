<?php

return [
    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Configura le impostazioni di LSCache per migliorare le prestazioni.',
                'title' => 'LiteSpeed Cache',

                'configuration' => [
                    'info'  => 'Gestisci l\'applicazione LiteSpeed Cache e le impostazioni correlate.',
                    'title' => 'Configurazione',

                    'cache-application' => [
                        'info'             => 'Imposta le opzioni dell\'applicazione cache.',
                        'title'            => 'Applicazione cache',
                        'title-info'       => 'Configura LiteSpeed Cache: abilita/disabilita la memorizzazione nella cache, imposta il TTL predefinito e scegli la memorizzazione solo per gli ospiti.',
                        'status'           => 'Stato',
                        'default-ttl'      => 'TTL predefinito (Time To Live)',
                        'default-ttl-info' => 'Imposta il tempo di vita predefinito per gli elementi memorizzati nella cache in secondi <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Maggiori informazioni</a>.',
                        'guest-only'       => 'Solo ospiti',
                        'guest-only-info'  => 'Abilita la memorizzazione nella cache solo per gli utenti ospiti. Se disabilitata, la cache verrà applicata a tutti gli utenti.',
                    ],
                ],
            ],
        ],
    ],
];
