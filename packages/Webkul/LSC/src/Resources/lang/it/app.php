<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'Cache LiteSpeed',
        ],

        'acl' => [
            'title' => 'Cache LiteSpeed',
            'view'  => 'Visualizza',
            'purge' => 'Svuota',
        ],

        'page' => [
            'title' => 'Cache LiteSpeed',
            'info'  => 'Rivedi i tag della cache ed esegui purghe mirate per il troubleshooting e la manutenzione in produzione.',
        ],

        'cards' => [
            'quick' => [
                'title' => 'Azioni rapide',
                'info'  => 'Usa azioni di svuotamento rapide per la home page o, se assolutamente necessario, per l’intera cache.',
            ],

            'debug' => [
                'title'         => 'Header di debug',
                'tag-label'     => 'Tag:',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => 'Policy:',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => 'Purge:',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => 'Svuota per categoria',
                'info'  => 'Cerca una categoria e svuota solo il tag basato sul suo ID.',
            ],

            'product' => [
                'title' => 'Svuota per prodotto',
                'info'  => 'Cerca un prodotto e svuota solo il tag basato sul suo ID.',
            ],

            'tag' => [
                'title' => 'Svuota per tag',
                'info'  => 'Inserisci uno o più tag LiteSpeed separati da virgole o spazi.',
            ],

            'url' => [
                'title' => 'Svuota per URL',
                'info'  => 'Invia una richiesta di purge per un percorso URL esatto (relativo o assoluto).',
            ],
        ],

        'actions' => [
            'purge-all'      => 'Svuota tutta la cache',
            'purge-home'     => 'Svuota la home page',
            'purge-category' => 'Svuota categoria',
            'purge-product'  => 'Svuota prodotto',
            'purge-tag'      => 'Svuota tag',
            'purge-url'      => 'Svuota URL',
        ],

        'badges' => [
            'id-tag'     => 'Tag ID',
            'manual'     => 'Manuale',
            'exact-path' => 'Percorso esatto',
        ],

        'fields' => [
            'category' => 'Categoria',
            'product'  => 'Prodotto',
            'tags'     => 'Tag',
            'url'      => 'URL',
        ],

        'labels' => [
            'target-tag' => 'Tag di destinazione:',
        ],

        'placeholders' => [
            'category' => 'Cerca categorie per nome, slug o ID',
            'product'  => 'Cerca prodotti per nome',
            'tags'     => 'Esempio: category_5 product_22 home',
            'url'      => 'Esempio: /footwears o https://example.com/footwears',
        ],

        'hints' => [
            'category' => 'Seleziona una categoria specifica prima di inviare.',
            'product'  => 'Seleziona un prodotto specifico prima di inviare.',
            'tags'     => 'Il carattere jolly (*) è bloccato qui. Usa invece “Svuota tutta la cache”.',
            'url'      => 'La richiesta viene normalizzata in un percorso storefront prima di essere inviata a LiteSpeed.',
        ],

        'confirm' => [
            'all'      => 'Vuoi svuotare l’intera cache LiteSpeed? Questa è un’azione distruttiva.',
            'home'     => 'Vuoi svuotare i tag della cache della home page?',
            'category' => 'Vuoi svuotare il tag della cache della categoria selezionata?',
            'product'  => 'Vuoi svuotare il tag della cache del prodotto selezionato?',
            'tag'      => 'Vuoi svuotare l’elenco di tag LiteSpeed fornito?',
            'url'      => 'Vuoi svuotare il percorso URL fornito?',
        ],

        'flash' => [
            'purge-all' => 'Richiesta di svuotamento completo della cache LiteSpeed inviata.',
            'home'      => 'Richiesta di svuotamento della home page inviata.',
            'category'  => 'Richiesta di svuotamento inviata per la categoria :name.',
            'product'   => 'Richiesta di svuotamento inviata per il prodotto :name.',
            'tags'      => 'Richiesta di svuotamento dei tag inviata.',
            'url'       => 'Richiesta di svuotamento URL inviata per :url.',
        ],
    ],

    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Configura le impostazioni LSCache per migliorare le prestazioni.',
                'title' => 'Cache LiteSpeed',

                'configuration' => [
                    'info'  => 'Gestisci l’applicazione LiteSpeed Cache e le impostazioni correlate.',
                    'title' => 'Configurazione',

                    'cache-application' => [
                        'info'             => 'Imposta le opzioni dell’applicazione cache.',
                        'title'            => 'Applicazione cache',
                        'title-info'       => 'Configura LiteSpeed Cache: abilita/disabilita la cache e imposta il TTL predefinito.',
                        'status'           => 'Stato',
                        'default-ttl'      => 'TTL predefinito (Time To Live)',
                        'default-ttl-info' => 'Imposta la durata predefinita degli elementi in cache in secondi <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Maggiori informazioni</a>.',
                        'debug-mode'       => 'Modalità debug',
                        'debug-mode-info'  => 'Quando abilitata, aggiunge header di debug che mostrano i tag LiteSpeed, le decisioni cache-control e le operazioni di purge per richiesta.',
                        'cache-path'       => 'Percorso cache',
                        'cache-path-info'  => 'Percorso completo alla directory di cache privata LiteSpeed. Predefinito: /usr/local/lsws/cachedata/priv (Enterprise) o /tmp/lshttpd/lscache (OpenLiteSpeed).',
                    ],
                ],
            ],
        ],
    ],
];
