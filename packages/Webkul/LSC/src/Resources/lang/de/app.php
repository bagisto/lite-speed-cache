<?php

return [
    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'LSCache-Einstellungen zur Leistungsverbesserung konfigurieren.',
                'title' => 'LiteSpeed Cache',

                'configuration' => [
                    'info'  => 'Verwalten Sie die LiteSpeed Cache-Anwendung und die zugehörigen Einstellungen.',
                    'title' => 'Konfiguration',

                    'cache-application' => [
                        'default-ttl-info' => 'Legen Sie die Standard-Lebensdauer für zwischengespeicherte Einträge in Sekunden fest <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Mehr Infos</a>.',
                        'default-ttl'      => 'Standard-TTL (Sekunden)',
                        'guest-only-info'  => 'Caching nur für Gäste aktivieren. Wenn deaktiviert, gilt das Caching für alle Benutzer.',
                        'guest-only'       => 'Nur Gäste',
                        'info'             => 'Optionen der Cache-Anwendung festlegen.',
                        'status'           => 'Status',
                        'title-info'       => 'LSCache konfigurieren: Caching aktivieren/deaktivieren, Standard-TTL festlegen und Caching nur für Gäste auswählen.',
                        'title'            => 'Cache-Anwendung',
                    ],
                ],
            ],
        ],
    ],
];
