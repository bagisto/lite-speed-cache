<?php

return [
    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Configure los ajustes de LSCache para mejorar el rendimiento.',
                'title' => 'Caché de LiteSpeed',

                'configuration' => [
                    'info'  => 'Administre la aplicación LiteSpeed Cache y los ajustes relacionados.',
                    'title' => 'Configuración',

                    'cache-application' => [
                        'info'             => 'Configure las opciones de la aplicación de caché.',
                        'title'            => 'Aplicación de caché',
                        'title-info'       => 'Configure LiteSpeed Cache: habilite o deshabilite la caché, establezca el TTL predeterminado y elija la opción solo invitados.',
                        'status'           => 'Estado',
                        'default-ttl'      => 'TTL predeterminado (Tiempo de vida)',
                        'default-ttl-info' => 'Establezca el tiempo de vida predeterminado para los elementos en caché en segundos <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Más información</a>.',
                        'guest-only'       => 'Solo invitados',
                        'guest-only-info'  => 'Habilite el caché solo para usuarios invitados. Si está deshabilitado, el caché se aplicará a todos los usuarios.',
                    ],
                ],
            ],
        ],
    ],
];
