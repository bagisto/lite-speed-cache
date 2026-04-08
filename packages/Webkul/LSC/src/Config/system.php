<?php

return [
    /**
     * LiteSpeedCache Configuration.
     */
    [
        'key'  => 'lsc',
        'name' => 'lsc::app.configuration.index.lsc.title',
        'info' => 'lsc::app.configuration.index.lsc.info',
        'sort' => 1,
    ], [
        'key'  => 'lsc.configuration',
        'name' => 'lsc::app.configuration.index.lsc.title',
        'info' => 'lsc::app.configuration.index.lsc.configuration.info',
        'icon' => 'settings/store.svg',
        'sort' => 1,
    ], [
        'key'    => 'lsc.configuration.cache_application',
        'name'   => 'lsc::app.configuration.index.lsc.configuration.cache-application.title',
        'info'   => 'lsc::app.configuration.index.lsc.configuration.cache-application.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'    => 'active',
                'title'   => 'lsc::app.configuration.index.lsc.configuration.cache-application.status',
                'type'    => 'boolean',
                'default' => false,
            ], [
                'name'       => 'default_ttl',
                'title'      => 'lsc::app.configuration.index.lsc.configuration.cache-application.default-ttl',
                'info'       => 'lsc::app.configuration.index.lsc.configuration.cache-application.default-ttl-info',
                'type'       => 'text',
                'depends'    => 'active:1',
                'validation' => 'required_if:active,1|integer|min:0|max:86400',
                'default'    => 3600,
            ], [
                'name'       => 'guest_only',
                'title'      => 'lsc::app.configuration.index.lsc.configuration.cache-application.guest-only',
                'info'       => 'lsc::app.configuration.index.lsc.configuration.cache-application.guest-only-info',
                'type'       => 'boolean',
                'depends'    => 'active:1',
                'validation' => 'required_if:active,1',
                'default'    => true,
            ],
        ],
    ],
];
