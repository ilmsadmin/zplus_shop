<?php

return [
    [
        'key'        => 'warranty',
        'name'       => 'warranty::app.acl.warranty',
        'route'      => 'admin.warranty.index',
        'sort'       => 6,
    ], [
        'key'        => 'warranty.index',
        'name'       => 'warranty::app.acl.warranties',
        'route'      => 'admin.warranty.index',
        'sort'       => 1,
    ], [
        'key'        => 'warranty.create',
        'name'       => 'warranty::app.acl.create',
        'route'      => 'admin.warranty.create',
        'sort'       => 2,
    ], [
        'key'        => 'warranty.edit',
        'name'       => 'warranty::app.acl.edit',
        'route'      => 'admin.warranty.edit',
        'sort'       => 3,
    ], [
        'key'        => 'warranty.delete',
        'name'       => 'warranty::app.acl.delete',
        'route'      => 'admin.warranty.destroy',
        'sort'       => 4,
    ], [
        'key'        => 'warranty.packages',
        'name'       => 'warranty::app.acl.packages',
        'route'      => 'admin.warranty.packages.index',
        'sort'       => 5,
    ], [
        'key'        => 'warranty.packages.create',
        'name'       => 'warranty::app.acl.packages-create',
        'route'      => 'admin.warranty.packages.create',
        'sort'       => 6,
    ], [
        'key'        => 'warranty.packages.edit',
        'name'       => 'warranty::app.acl.packages-edit',
        'route'      => 'admin.warranty.packages.edit',
        'sort'       => 7,
    ], [
        'key'        => 'warranty.packages.delete',
        'name'       => 'warranty::app.acl.packages-delete',
        'route'      => 'admin.warranty.packages.destroy',
        'sort'       => 8,
    ],
];
