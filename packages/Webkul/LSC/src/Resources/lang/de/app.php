<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'LiteSpeed Cache',
        ],

        'acl' => [
            'title' => 'LiteSpeed Cache',
            'view'  => 'Ansehen',
            'purge' => 'Leeren',
        ],

        'page' => [
            'title' => 'LiteSpeed Cache',
            'info'  => 'Überprüfen Sie Cache-Tags und führen Sie gezielte Löschvorgänge für Fehlerbehebung und Wartung in der Produktion aus.',
        ],

        'cards' => [
            'quick' => [
                'title' => 'Schnellaktionen',
                'info'  => 'Verwenden Sie schnelle Löschaktionen für die Startseite oder, wenn unbedingt erforderlich, für den gesamten Cache.',
            ],

            'debug' => [
                'title'         => 'Debug-Header',
                'tag-label'     => 'Tag:',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => 'Richtlinie:',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => 'Löschen:',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => 'Nach Kategorie löschen',
                'info'  => 'Suchen Sie eine Kategorie und löschen Sie nur das ID-basierte Tag.',
            ],

            'product' => [
                'title' => 'Nach Produkt löschen',
                'info'  => 'Suchen Sie ein Produkt und löschen Sie nur das ID-basierte Tag.',
            ],

            'tag' => [
                'title' => 'Nach Tag löschen',
                'info'  => 'Geben Sie ein oder mehrere LiteSpeed-Tags ein, getrennt durch Kommas oder Leerzeichen.',
            ],

            'url' => [
                'title' => 'Nach URL löschen',
                'info'  => 'Senden Sie eine genaue URL-Pfad-Löschanforderung für einen relativen oder absoluten Storefront-Link.',
            ],
        ],

        'actions' => [
            'purge-all'      => 'Gesamten Cache leeren',
            'purge-home'     => 'Startseite leeren',
            'purge-category' => 'Kategorie leeren',
            'purge-product'  => 'Produkt leeren',
            'purge-tag'      => 'Tag leeren',
            'purge-url'      => 'URL leeren',
        ],

        'badges' => [
            'id-tag'     => 'ID-Tag',
            'manual'     => 'Manuell',
            'exact-path' => 'Exakter Pfad',
        ],

        'fields' => [
            'category' => 'Kategorie',
            'product'  => 'Produkt',
            'tags'     => 'Tags',
            'url'      => 'URL',
        ],

        'labels' => [
            'target-tag' => 'Ziel-Tag:',
        ],

        'placeholders' => [
            'category' => 'Kategorien nach Name, Slug oder ID suchen',
            'product'  => 'Produkte nach Name suchen',
            'tags'     => 'Beispiel: category_5 product_22 home',
            'url'      => 'Beispiel: /footwears oder https://example.com/footwears',
        ],

        'hints' => [
            'category' => 'Wählen Sie vor dem Absenden eine konkrete Kategorie aus.',
            'product'  => 'Wählen Sie vor dem Absenden ein konkretes Produkt aus.',
            'tags'     => 'Wildcard (*) ist hier absichtlich deaktiviert. Verwenden Sie stattdessen „Gesamten Cache leeren“.',
            'url'      => 'Die Anfrage wird vor der Übergabe an LiteSpeed auf einen Storefront-Pfad normalisiert.',
        ],

        'confirm' => [
            'all'      => 'Den gesamten LiteSpeed-Cache leeren? Dies ist eine destruktive Aktion.',
            'home'     => 'Cache-Tags der Startseite leeren?',
            'category' => 'Cache-Tag der ausgewählten Kategorie leeren?',
            'product'  => 'Cache-Tag des ausgewählten Produkts leeren?',
            'tag'      => 'Die angegebenen LiteSpeed-Tags leeren?',
            'url'      => 'Den angegebenen URL-Pfad leeren?',
        ],

        'flash' => [
            'purge-all' => 'Anfrage zum vollständigen Leeren des LiteSpeed-Caches gesendet.',
            'home'      => 'Anfrage zum Leeren der Startseite gesendet.',
            'category'  => 'Leeranfrage für Kategorie :name gesendet.',
            'product'   => 'Leeranfrage für Produkt :name gesendet.',
            'tags'      => 'Tag-Leeranfrage gesendet.',
            'url'       => 'URL-Leeranfrage für :url gesendet.',
        ],
    ],

    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Konfigurieren Sie die LSCache-Einstellungen zur Leistungsverbesserung.',
                'title' => 'LiteSpeed Cache',

                'configuration' => [
                    'info'  => 'Verwalten Sie die LiteSpeed-Cache-Anwendung und zugehörige Einstellungen.',
                    'title' => 'Konfiguration',

                    'cache-application' => [
                        'info'             => 'Optionen der Cache-Anwendung festlegen.',
                        'title'            => 'Cache-Anwendung',
                        'title-info'       => 'LiteSpeed Cache konfigurieren: Cache aktivieren/deaktivieren und Standard-TTL festlegen.',
                        'status'           => 'Status',
                        'default-ttl'      => 'Standard-TTL (Time To Live)',
                        'default-ttl-info' => 'Legen Sie die Standardlebensdauer für Cache-Elemente in Sekunden fest <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Mehr Infos</a>.',
                        'debug-mode'       => 'Debug-Modus',
                        'debug-mode-info'  => 'Wenn aktiviert, werden Debug-Header hinzugefügt, die LiteSpeed-Tags, Cache-Control-Entscheidungen und anfragebezogene Löschvorgänge anzeigen.',
                        'cache-path'       => 'Cache-Pfad',
                        'cache-path-info'  => 'Vollständiger Pfad zum privaten LiteSpeed-Cache-Verzeichnis. Standard: /usr/local/lsws/cachedata/priv (Enterprise) oder /tmp/lshttpd/lscache (OpenLiteSpeed).',
                    ],
                ],
            ],
        ],
    ],
];
