<?php

return [
    [
        'key'   => 'settings.other_settings.api_keys',
        'name'  => 'api_key::app.acl.title',
        'route' => [
            'admin.settings.api_keys.index',
            'admin.settings.api_keys.store',
            'admin.settings.api_keys.destroy',
        ],
        'sort'  => 4,
    ],
];
