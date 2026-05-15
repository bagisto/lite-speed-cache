<?php

return [
    'admin' => [
        'menu' => [
            'title' => 'Caché de LiteSpeed',
        ],

        'acl' => [
            'title' => 'Caché de LiteSpeed',
            'view'  => 'Ver',
            'purge' => 'Purgar',
        ],

        'page' => [
            'title' => 'Caché de LiteSpeed',
            'info'  => 'Revisa las etiquetas de caché y ejecuta purgas específicas para la resolución de problemas y mantenimiento en producción.',
        ],

        'cards' => [
            'quick' => [
                'title' => 'Acciones rápidas',
                'info'  => 'Usa acciones de purga rápidas para la página de inicio o, cuando sea absolutamente necesario, para toda la caché.',
            ],

            'debug' => [
                'title'         => 'Encabezados de depuración',
                'tag-label'     => 'Etiqueta:',
                'tag-header'    => 'X-DEBUG-LiteSpeed-Tag',
                'policy-label'  => 'Política:',
                'policy-header' => 'X-DEBUG-LiteSpeed-CacheControl',
                'purge-label'   => 'Purgar:',
                'purge-header'  => 'X-DEBUG-LiteSpeed-Purge',
            ],

            'category' => [
                'title' => 'Purgar por categoría',
                'info'  => 'Busca una categoría y purga solo su etiqueta basada en ID.',
            ],

            'product' => [
                'title' => 'Purgar por producto',
                'info'  => 'Busca un producto y purga solo su etiqueta basada en ID.',
            ],

            'tag' => [
                'title' => 'Purgar por etiqueta',
                'info'  => 'Introduce una o más etiquetas de LiteSpeed separadas por comas o espacios.',
            ],

            'url' => [
                'title' => 'Purgar por URL',
                'info'  => 'Envía una solicitud de purga para una ruta URL exacta (relativa o absoluta).',
            ],
        ],

        'actions' => [
            'purge-all'      => 'Purgar toda la caché',
            'purge-home'     => 'Purgar página de inicio',
            'purge-category' => 'Purgar categoría',
            'purge-product'  => 'Purgar producto',
            'purge-tag'      => 'Purgar etiqueta',
            'purge-url'      => 'Purgar URL',
        ],

        'badges' => [
            'id-tag'     => 'Etiqueta ID',
            'manual'     => 'Manual',
            'exact-path' => 'Ruta exacta',
        ],

        'fields' => [
            'category' => 'Categoría',
            'product'  => 'Producto',
            'tags'     => 'Etiquetas',
            'url'      => 'URL',
        ],

        'labels' => [
            'target-tag' => 'Etiqueta objetivo:',
        ],

        'placeholders' => [
            'category' => 'Buscar categorías por nombre, slug o ID',
            'product'  => 'Buscar productos por nombre',
            'tags'     => 'Ejemplo: category_5 product_22 home',
            'url'      => 'Ejemplo: /footwears o https://example.com/footwears',
        ],

        'hints' => [
            'category' => 'Elige una categoría concreta antes de enviar.',
            'product'  => 'Elige un producto concreto antes de enviar.',
            'tags'     => 'El comodín (*) está bloqueado aquí. Usa “Purgar toda la caché” en su lugar.',
            'url'      => 'La solicitud se normaliza a una ruta de la tienda antes de enviarse a LiteSpeed.',
        ],

        'confirm' => [
            'all'      => '¿Purgar toda la caché de LiteSpeed? Esta es una acción destructiva.',
            'home'     => '¿Purgar las etiquetas de caché de la página de inicio?',
            'category' => '¿Purgar la etiqueta de caché de la categoría seleccionada?',
            'product'  => '¿Purgar la etiqueta de caché del producto seleccionado?',
            'tag'      => '¿Purgar la lista de etiquetas de LiteSpeed proporcionada?',
            'url'      => '¿Purgar la ruta URL proporcionada?',
        ],

        'flash' => [
            'purge-all' => 'Solicitud de purga total de la caché de LiteSpeed enviada.',
            'home'      => 'Solicitud de purga de la página de inicio enviada.',
            'category'  => 'Solicitud de purga enviada para la categoría :name.',
            'product'   => 'Solicitud de purga enviada para el producto :name.',
            'tags'      => 'Solicitud de purga de etiquetas enviada.',
            'url'       => 'Solicitud de purga de URL enviada para :url.',
        ],
    ],

    'configuration' => [
        'index' => [
            'lsc' => [
                'info'  => 'Configura los ajustes de LSCache para mejorar el rendimiento.',
                'title' => 'Caché de LiteSpeed',

                'configuration' => [
                    'info'  => 'Gestiona la aplicación LiteSpeed Cache y sus configuraciones relacionadas.',
                    'title' => 'Configuración',

                    'cache-application' => [
                        'info'             => 'Establece opciones de la aplicación de caché.',
                        'title'            => 'Aplicación de caché',
                        'title-info'       => 'Configura LiteSpeed Cache: habilitar/deshabilitar caché y establecer el TTL predeterminado.',
                        'status'           => 'Estado',
                        'default-ttl'      => 'TTL predeterminado (Tiempo de vida)',
                        'default-ttl-info' => 'Establece el tiempo de vida predeterminado para los elementos en caché en segundos <a class="text-blue-600 hover:underline" href="https://docs.litespeedtech.com/lscache/lsclaravel/settings/#cache-control" target="_blank">Más información</a>.',
                        'debug-mode'       => 'Modo de depuración',
                        'debug-mode-info'  => 'Cuando está habilitado, agrega encabezados de depuración que muestran etiquetas de LiteSpeed, decisiones de cache-control y operaciones de purga por solicitud.',
                        'cache-path'       => 'Ruta de caché',
                        'cache-path-info'  => 'Ruta completa al directorio de caché privado de LiteSpeed. Predeterminado: /usr/local/lsws/cachedata/priv (Enterprise) o /tmp/lshttpd/lscache (OpenLiteSpeed).',
                    ],
                ],
            ],
        ],
    ],
];
