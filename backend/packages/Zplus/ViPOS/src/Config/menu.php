<?php

return [
    /**
     * ViPOS - Point of Sale System
     */
    [
        'key'        => 'vipos',
        'name'       => 'vipos::app.components.layouts.sidebar.vipos',
        'route'      => 'admin.vipos.index',
        'sort'       => 2.5, // Placed between Sales (2) and Catalog (3)
        'icon'       => 'icon-store',
        'icon-class' => 'pos-icon',
    ],
    [
        'key'        => 'vipos.dashboard',
        'name'       => 'vipos::app.components.layouts.sidebar.dashboard',
        'route'      => 'admin.vipos.index',
        'sort'       => 1,
        'icon'       => '',
    ],
    [
        'key'        => 'vipos.sessions',
        'name'       => 'vipos::app.components.layouts.sidebar.sessions',
        'route'      => 'admin.vipos.sessions.index',
        'sort'       => 2,
        'icon'       => '',
    ],
    [
        'key'        => 'vipos.transactions',
        'name'       => 'vipos::app.components.layouts.sidebar.transactions', 
        'route'      => 'admin.vipos.transactions.index',
        'sort'       => 3,
        'icon'       => '',
    ],
];
