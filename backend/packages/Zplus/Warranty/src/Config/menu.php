<?php

return [
    /**
     * Warranty Management
     */
    [
        'key'        => 'warranty',
        'name'       => 'warranty::app.components.layouts.sidebar.warranty',
        'route'      => 'admin.warranty.index',
        'sort'       => 2.6, // Placed after ViPOS (2.5)
        'icon'       => 'icon-settings',
    ],
    [
        'key'        => 'warranty.index',
        'name'       => 'warranty::app.components.layouts.sidebar.warranties',
        'route'      => 'admin.warranty.index',
        'sort'       => 1,
        'icon'       => '',
    ],
    [
        'key'        => 'warranty.packages',
        'name'       => 'warranty::app.components.layouts.sidebar.packages',
        'route'      => 'admin.warranty.packages.index',
        'sort'       => 2,
        'icon'       => '',
    ],
];