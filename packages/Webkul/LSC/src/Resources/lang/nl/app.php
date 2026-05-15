<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'LiteSpeed-cache',
        ],

        'acl' => [
            'title' => 'LiteSpeed-cache',
            'view'  => 'Bekijken',
            'purge' => 'Opschonen',
        ],

        'page' => [
            'title' => 'LiteSpeed-cache',
            'info'  => 'Bekijk cache-tags en voer gerichte opschoonacties uit voor probleemoplossing en onderhoud in productie.',
        ],

        'cards' => [
            'quick' => [
                'title' => 'Snelle acties',
                'info'  => 'Gebruik snelle opschoonacties voor de startpagina of, indien nodig, voor de volledige cache.',
            ],

            'debug' => [
                'title'         => 'Debug-headers',
                'tag-label'     => 'Tag:',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => 'Beleid:',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => 'Opschonen:',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => 'Opschonen per categorie',
                'info'  => 'Zoek een categorie en verwijder alleen de op ID gebaseerde tag.',
            ],

            'product' => [
                'title' => 'Opschonen per product',
                'info'  => 'Zoek een product en verwijder alleen de op ID gebaseerde tag.',
            ],

            'tag' => [
                'title' => 'Opschonen per tag',
                'info'  => 'Voer één of meer LiteSpeed-tags in, gescheiden door komma’s of spaties.',
            ],

            'url' => [
                'title' => 'Opschonen per URL',
                'info'  => 'Stuur een exacte URL-pad opschoonverzoek voor een relatieve of absolute storefront-URL.',
            ],
        ],

        'actions' => [
            'purge-all'      => 'Volledige cache opschonen',
            'purge-home'     => 'Startpagina opschonen',
            'purge-category' => 'Categorie opschonen',
            'purge-product'  => 'Product opschonen',
            'purge-tag'      => 'Tag opschonen',
            'purge-url'      => 'URL opschonen',
        ],

        'badges' => [
            'id-tag'     => 'ID-tag',
            'manual'     => 'Handmatig',
            'exact-path' => 'Exact pad',
        ],

        'fields' => [
            'category' => 'Categorie',
            'product'  => 'Product',
            'tags'     => 'Tags',
            'url'      => 'URL',
        ],

        'labels' => [
            'target-tag' => 'Doel-tag:',
        ],

        'placeholders' => [
            'category' => 'Zoek categorieën op naam, slug of ID',
            'product'  => 'Zoek producten op naam',
            'tags'     => 'Voorbeeld: category_5 product_22 home',
            'url'      => 'Voorbeeld: /footwears of https://example.com/footwears',
        ],

        'hints' => [
            'category' => 'Kies een exacte categorie voordat u verzendt.',
            'product'  => 'Kies een exact product voordat u verzendt.',
            'tags'     => 'Wildcard (*) is hier geblokkeerd. Gebruik in plaats daarvan “Volledige cache opschonen”.',
            'url'      => 'Het verzoek wordt genormaliseerd naar een storefront-pad voordat het naar LiteSpeed wordt verzonden.',
        ],

        'confirm' => [
            'all'      => 'De volledige LiteSpeed-cache opschonen? Dit is een destructieve actie.',
            'home'     => 'Cache-tags van de startpagina opschonen?',
            'category' => 'Cache-tag van de geselecteerde categorie opschonen?',
            'product'  => 'Cache-tag van het geselecteerde product opschonen?',
            'tag'      => 'De opgegeven LiteSpeed-tags opschonen?',
            'url'      => 'Het opgegeven URL-pad opschonen?',
        ],

        'flash' => [
            'purge-all' => 'Verzoek voor volledige LiteSpeed-cache opschoning verzonden.',
            'home'      => 'Verzoek voor opschoning van de startpagina verzonden.',
            'category'  => 'Opschoonverzoek verzonden voor categorie :name.',
            'product'   => 'Opschoonverzoek verzonden voor product :name.',
            'tags'      => 'Opschoonverzoek voor tags verzonden.',
            'url'       => 'Opschoonverzoek voor URL :url verzonden.',
        ],
    ],

    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Configureer LSCache-instellingen om de prestaties te verbeteren.',
                'title' => 'LiteSpeed-cache',

                'configuration' => [
                    'info'  => 'Beheer de LiteSpeed Cache-applicatie en gerelateerde instellingen.',
                    'title' => 'Configuratie',

                    'cache-application' => [
                        'info'             => 'Stel cache-applicatieopties in.',
                        'title'            => 'Cache-applicatie',
                        'title-info'       => 'Configureer LiteSpeed Cache: schakel cache in/uit en stel de standaard TTL in.',
                        'status'           => 'Status',
                        'default-ttl'      => 'Standaard TTL (Time To Live)',
                        'default-ttl-info' => 'Stel de standaard levensduur van cache-items in seconden in <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Meer info</a>.',
                        'debug-mode'       => 'Debug-modus',
                        'debug-mode-info'  => 'Wanneer ingeschakeld, voegt dit debug-headers toe die LiteSpeed-tags, cache-control beslissingen en purge-acties per verzoek tonen.',
                        'cache-path'       => 'Cachepad',
                        'cache-path-info'  => 'Volledig pad naar de private LiteSpeed-cachemap. Standaard: /usr/local/lsws/cachedata/priv (Enterprise) of /tmp/lshttpd/lscache (OpenLiteSpeed).',
                    ],
                ],
            ],
        ],
    ],
];
