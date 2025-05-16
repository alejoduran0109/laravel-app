<?php

return [
    'auth' => [
        'guards' => [
            'web' => [
                'driver' => 'session',
                'provider' => 'users',
            ],
        ],
        'default_guard' => 'web',
    ],
    'panels' => [
        'admin' => [
            'url' => env('FILAMENT_ADMIN_URL', 'admin'),
            'pages' => [
                'dashboard' => \Filament\Pages\Dashboard::class,
            ],
            'widgets' => [
                // Add your widgets here
            ],
        ],
    ],
    'plugins' => [
        // Add your plugins here
    ],
    'default_panel' => 'admin',
    'default_panel_url' => env('FILAMENT_ADMIN_URL', 'admin'),
    'default_panel_domain' => env('FILAMENT_ADMIN_DOMAIN', null),
    'default_panel_path' => env('FILAMENT_ADMIN_PATH', '/'),
    'default_panel_domain_path' => env('FILAMENT_ADMIN_DOMAIN_PATH', '/'),
    'default_panel_domain_path_prefix' => env('FILAMENT_ADMIN_DOMAIN_PATH_PREFIX', ''),
    'default_panel_domain_path_suffix' => env('FILAMENT_ADMIN_DOMAIN_PATH_SUFFIX', ''),
    'default_panel_domain_path_separator' => env('FILAMENT_ADMIN_DOMAIN_PATH_SEPARATOR', '/'),
    'default_panel_domain_path_separator_prefix' => env('FILAMENT_ADMIN_DOMAIN_PATH_SEPARATOR_PREFIX', ''),
    'default_panel_domain_path_separator_suffix' => env('FILAMENT_ADMIN_DOMAIN_PATH_SEPARATOR_SUFFIX', ''),
    'default_panel_domain_path_separator_prefix_suffix' => env('FILAMENT_ADMIN_DOMAIN_PATH_SEPARATOR_PREFIX_SUFFIX', ''),
    'default_panel_domain_path_separator_prefix_suffix_separator' => env('FILAMENT_ADMIN_DOMAIN_PATH_SEPARATOR_PREFIX_SUFFIX_SEPARATOR', '/'),
];
