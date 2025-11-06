<?php

return [
    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Configurer les paramètres de LSCache pour améliorer les performances.',
                'title' => 'LiteSpeed Cache',

                'configuration' => [
                    'info'  => 'Gérer l\'application LiteSpeed Cache et les paramètres associés.',
                    'title' => 'Configuration',

                    'cache-application' => [
                        'info'             => 'Définir les options de l\'application de cache.',
                        'title'            => 'Application de cache',
                        'title-info'       => 'Configurez LiteSpeed Cache : activez/désactivez la mise en cache, définissez le TTL par défaut et choisissez la mise en cache uniquement pour les invités.',
                        'status'           => 'Statut',
                        'default-ttl'      => 'TTL par défaut (Durée de vie)',
                        'default-ttl-info' => 'Définissez le temps de vie par défaut des éléments mis en cache en secondes <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Plus d\'infos</a>.',
                        'guest-only'       => 'Uniquement pour les invités',
                        'guest-only-info'  => 'Activez la mise en cache uniquement pour les utilisateurs invités. Si désactivé, la mise en cache sera appliquée à tous les utilisateurs.',
                    ],
                ],
            ],
        ],
    ],
];
