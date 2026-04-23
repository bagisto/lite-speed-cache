<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'Cache LiteSpeed',
        ],

        'acl' => [
            'title' => 'Cache LiteSpeed',
            'view'  => 'Voir',
            'purge' => 'Purger',
        ],

        'page' => [
            'title' => 'Cache LiteSpeed',
            'info'  => 'Examinez les balises de cache et déclenchez des purges ciblées pour le dépannage et la maintenance en production.',
        ],

        'cards' => [
            'quick' => [
                'title' => 'Actions rapides',
                'info'  => 'Utilisez des purges rapides pour la page d’accueil ou, si nécessaire, pour l’ensemble du cache.',
            ],

            'debug' => [
                'title'         => 'En-têtes de débogage',
                'tag-label'     => 'Balise :',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => 'Politique :',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => 'Purge :',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => 'Purger par catégorie',
                'info'  => 'Recherchez une catégorie et purgez uniquement la balise basée sur son ID.',
            ],

            'product' => [
                'title' => 'Purger par produit',
                'info'  => 'Recherchez un produit et purgez uniquement la balise basée sur son ID.',
            ],

            'tag' => [
                'title' => 'Purger par balise',
                'info'  => 'Saisissez une ou plusieurs balises LiteSpeed séparées par des virgules ou des espaces.',
            ],

            'url' => [
                'title' => 'Purger par URL',
                'info'  => 'Envoyez une requête de purge pour un chemin URL exact (relatif ou absolu).',
            ],
        ],

        'actions' => [
            'purge-all'      => 'Purger tout le cache',
            'purge-home'     => 'Purger la page d’accueil',
            'purge-category' => 'Purger la catégorie',
            'purge-product'  => 'Purger le produit',
            'purge-tag'      => 'Purger la balise',
            'purge-url'      => 'Purger l’URL',
        ],

        'badges' => [
            'id-tag'     => 'Balise ID',
            'manual'     => 'Manuel',
            'exact-path' => 'Chemin exact',
        ],

        'fields' => [
            'category' => 'Catégorie',
            'product'  => 'Produit',
            'tags'     => 'Balises',
            'url'      => 'URL',
        ],

        'labels' => [
            'target-tag' => 'Balise cible :',
        ],

        'placeholders' => [
            'category' => 'Rechercher des catégories par nom, slug ou ID',
            'product'  => 'Rechercher des produits par nom',
            'tags'     => 'Exemple : category_5 product_22 home',
            'url'      => 'Exemple : /footwears ou https://example.com/footwears',
        ],

        'hints' => [
            'category' => 'Choisissez une catégorie précise avant de soumettre.',
            'product'  => 'Choisissez un produit précis avant de soumettre.',
            'tags'     => 'Le joker (*) est bloqué ici. Utilisez « Purger tout le cache » à la place.',
            'url'      => 'La requête est normalisée en chemin storefront avant d’être envoyée à LiteSpeed.',
        ],

        'confirm' => [
            'all'      => 'Purger tout le cache LiteSpeed ? Cette action est destructive.',
            'home'     => 'Purger les balises de cache de la page d’accueil ?',
            'category' => 'Purger la balise de cache de la catégorie sélectionnée ?',
            'product'  => 'Purger la balise de cache du produit sélectionné ?',
            'tag'      => 'Purger la liste de balises LiteSpeed fournie ?',
            'url'      => 'Purger le chemin URL fourni ?',
        ],

        'flash' => [
            'purge-all' => 'Requête de purge complète du cache LiteSpeed envoyée.',
            'home'      => 'Requête de purge de la page d’accueil envoyée.',
            'category'  => 'Requête de purge envoyée pour la catégorie :name.',
            'product'   => 'Requête de purge envoyée pour le produit :name.',
            'tags'      => 'Requête de purge des balises envoyée.',
            'url'       => 'Requête de purge URL envoyée pour :url.',
        ],
    ],

    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Configurez les paramètres LSCache pour améliorer les performances.',
                'title' => 'Cache LiteSpeed',

                'configuration' => [
                    'info'  => 'Gérez l’application LiteSpeed Cache et les paramètres associés.',
                    'title' => 'Configuration',

                    'cache-application' => [
                        'info'             => 'Définir les options de l’application de cache.',
                        'title'            => 'Application de cache',
                        'title-info'       => 'Configurez LiteSpeed Cache : activer/désactiver le cache et définir le TTL par défaut.',
                        'status'           => 'Statut',
                        'default-ttl'      => 'TTL par défaut (durée de vie)',
                        'default-ttl-info' => 'Définissez la durée de vie par défaut des éléments en cache en secondes <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Plus d’informations</a>.',
                        'debug-mode'       => 'Mode débogage',
                        'debug-mode-info'  => 'Lorsqu’il est activé, ajoute des en-têtes de débogage affichant les balises LiteSpeed, les décisions de cache-control et les opérations de purge par requête.',
                        'cache-path'       => 'Chemin du cache',
                        'cache-path-info'  => 'Chemin complet vers le répertoire de cache privé LiteSpeed. Par défaut : /usr/local/lsws/cachedata/priv (Enterprise) ou /tmp/lshttpd/lscache (OpenLiteSpeed).',
                    ],
                ],
            ],
        ],
    ],
];
