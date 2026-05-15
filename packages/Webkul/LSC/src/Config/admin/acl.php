<?php

return [
    [
        'key'   => 'settings.litespeed_cache',
        'name'  => 'lsc::app.admin.acl.title',
        'route' => 'admin.settings.lsc.index',
        'sort'  => 95,
    ], [
        'key'   => 'settings.litespeed_cache.view',
        'name'  => 'lsc::app.admin.acl.view',
        'route' => 'admin.settings.lsc.index',
        'sort'  => 1,
    ], [
        'key'   => 'settings.litespeed_cache.purge',
        'name'  => 'lsc::app.admin.acl.purge',
        'route' => 'admin.settings.lsc.purge.all',
        'sort'  => 2,
    ],
];
