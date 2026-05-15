<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'Memòria cau LiteSpeed',
        ],

        'acl' => [
            'title' => 'Memòria cau LiteSpeed',
            'view'  => 'Veure',
            'purge' => 'Purgar',
        ],

        'page' => [
            'title' => 'Memòria cau LiteSpeed',
            'info'  => 'Revisa les etiquetes de memòria cau i executa purgues específiques per a la resolució de problemes i el manteniment en producció.',
        ],

        'cards' => [
            'quick' => [
                'title' => 'Accions ràpides',
                'info'  => 'Utilitza accions ràpides de purga per a la pàgina inicial o, si és absolutament necessari, per a tota la memòria cau.',
            ],

            'debug' => [
                'title'         => 'Capçaleres de depuració',
                'tag-label'     => 'Etiqueta:',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => 'Política:',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => 'Purga:',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => 'Purgar per categoria',
                'info'  => 'Cerca una categoria i purga només l’etiqueta basada en el seu ID.',
            ],

            'product' => [
                'title' => 'Purgar per producte',
                'info'  => 'Cerca un producte i purga només l’etiqueta basada en el seu ID.',
            ],

            'tag' => [
                'title' => 'Purgar per etiqueta',
                'info'  => 'Introdueix una o més etiquetes LiteSpeed separades per comes o espais.',
            ],

            'url' => [
                'title' => 'Purgar per URL',
                'info'  => 'Envia una sol·licitud de purga per a una ruta URL exacta (relativa o absoluta).',
            ],
        ],

        'actions' => [
            'purge-all'      => 'Purgar tota la memòria cau',
            'purge-home'     => 'Purgar la pàgina inicial',
            'purge-category' => 'Purgar categoria',
            'purge-product'  => 'Purgar producte',
            'purge-tag'      => 'Purgar etiqueta',
            'purge-url'      => 'Purgar URL',
        ],

        'badges' => [
            'id-tag'     => 'Etiqueta ID',
            'manual'     => 'Manual',
            'exact-path' => 'Ruta exacta',
        ],

        'fields' => [
            'category' => 'Categoria',
            'product'  => 'Producte',
            'tags'     => 'Etiquetes',
            'url'      => 'URL',
        ],

        'labels' => [
            'target-tag' => 'Etiqueta objectiu:',
        ],

        'placeholders' => [
            'category' => 'Cerca categories per nom, slug o ID',
            'product'  => 'Cerca productes per nom',
            'tags'     => 'Exemple: category_5 product_22 home',
            'url'      => 'Exemple: /footwears o https://example.com/footwears',
        ],

        'hints' => [
            'category' => 'Selecciona una coincidència de categoria concreta abans d’enviar.',
            'product'  => 'Selecciona una coincidència de producte concreta abans d’enviar.',
            'tags'     => 'El comodí (*) està bloquejat aquí. Utilitza “Purgar-ho tot” en el seu lloc.',
            'url'      => 'La sol·licitud es normalitza a una ruta de botiga abans que LiteSpeed la rebi.',
        ],

        'confirm' => [
            'all'      => 'Vols purgar tota la memòria cau LiteSpeed? Aquesta acció és destructiva.',
            'home'     => 'Vols purgar les etiquetes de memòria cau de la pàgina inicial?',
            'category' => 'Vols purgar l’etiqueta de memòria cau de la categoria seleccionada?',
            'product'  => 'Vols purgar l’etiqueta de memòria cau del producte seleccionat?',
            'tag'      => 'Vols purgar la llista d’etiquetes LiteSpeed proporcionada?',
            'url'      => 'Vols purgar la ruta URL proporcionada?',
        ],

        'flash' => [
            'purge-all' => 'S’ha enviat la sol·licitud de purga total de la memòria cau LiteSpeed.',
            'home'      => 'S’ha enviat la sol·licitud de purga de la pàgina inicial.',
            'category'  => 'S’ha enviat la sol·licitud de purga per a la categoria :name.',
            'product'   => 'S’ha enviat la sol·licitud de purga per al producte :name.',
            'tags'      => 'S’ha enviat la sol·licitud de purga d’etiquetes LiteSpeed.',
            'url'       => 'S’ha enviat la sol·licitud de purga de la URL :url.',
        ],
    ],

    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Configura els paràmetres de LSCache per millorar el rendiment.',
                'title' => 'Memòria cau LiteSpeed',

                'configuration' => [
                    'info'  => 'Gestiona l’aplicació LiteSpeed Cache i els paràmetres relacionats.',
                    'title' => 'Configuració',

                    'cache-application' => [
                        'info'             => 'Defineix opcions de l’aplicació de memòria cau.',
                        'title'            => 'Aplicació de memòria cau',
                        'title-info'       => 'Configura LiteSpeed Cache: activa/desactiva la memòria cau i estableix el TTL per defecte.',
                        'status'           => 'Estat',
                        'default-ttl'      => 'TTL per defecte (Temps de vida)',
                        'default-ttl-info' => 'Defineix el temps de vida per defecte dels elements en memòria cau en segons <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Més informació</a>.',
                        'debug-mode'       => 'Mode de depuració',
                        'debug-mode-info'  => 'Quan està activat, afegeix capçaleres de depuració lleugeres que mostren etiquetes LiteSpeed, decisions de control de memòria cau i operacions de purga per a inspecció.',
                        'cache-path'       => 'Ruta de la memòria cau',
                        'cache-path-info'  => 'Ruta completa al directori de memòria cau privada de LiteSpeed. Per defecte: /usr/local/lsws/cachedata/priv (Enterprise) o /tmp/lshttpd/lscache (OpenLiteSpeed).',
                    ],
                ],
            ],
        ],
    ],
];
