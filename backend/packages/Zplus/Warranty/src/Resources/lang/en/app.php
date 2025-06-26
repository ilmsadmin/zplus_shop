<?php

return [
    'components' => [
        'layouts' => [
            'sidebar' => [
                'warranty' => 'Warranty Management',
                'warranties' => 'Warranties',
                'packages' => 'Warranty Packages',
            ],
        ],
    ],

    'admin' => [
        'warranties' => [
            'index' => [
                'title' => 'Warranty Management',
                'create-btn' => 'Create New Warranty',
                'search-placeholder' => 'Search by warranty number, serial, customer name...',
                'export-btn' => 'Export Excel',
                'datagrid' => [
                    'warranty-number' => 'Warranty Number',
                    'product-name' => 'Product',
                    'product-serial' => 'Serial',
                    'customer-name' => 'Customer',
                    'customer-phone' => 'Phone',
                    'package' => 'Warranty Package',
                    'start-date' => 'Start Date',
                    'end-date' => 'End Date',
                    'status' => 'Status',
                    'actions' => 'Actions',
                    'view' => 'View',
                    'edit' => 'Edit',
                    'delete' => 'Delete',
                ],
            ],
            'create' => [
                'title' => 'Create New Warranty',
                'save-btn' => 'Save Warranty',
                'general' => 'General Information',
                'product-info' => 'Product Information',
                'customer-info' => 'Customer Information',
                'warranty-info' => 'Warranty Information',
            ],
            'edit' => [
                'title' => 'Edit Warranty',
                'update-btn' => 'Update',
            ],
            'show' => [
                'title' => 'Warranty Details',
                'warranty-details' => 'Warranty Information',
                'product-details' => 'Product Information',
                'customer-details' => 'Customer Information',
                'warranty-history' => 'Warranty History',
            ],
            'fields' => [
                'warranty-number' => 'Warranty Number',
                'warranty-package' => 'Warranty Package',
                'product' => 'Product',
                'product-serial' => 'Product Serial',
                'customer' => 'Customer',
                'customer-phone' => 'Phone Number',
                'customer-email' => 'Email',
                'purchase-date' => 'Purchase Date',
                'start-date' => 'Warranty Start Date',
                'end-date' => 'Warranty End Date',
                'order-number' => 'Order Number',
                'status' => 'Status',
                'notes' => 'Notes',
            ],
            'status' => [
                'active' => 'Active',
                'expired' => 'Expired',
                'claimed' => 'Claimed',
                'cancelled' => 'Cancelled',
            ],
        ],

        'packages' => [
            'index' => [
                'title' => 'Warranty Package Management',
                'create-btn' => 'Create New Package',
                'datagrid' => [
                    'name' => 'Package Name',
                    'duration' => 'Duration (Months)',
                    'price' => 'Price',
                    'warranties-count' => 'Warranties Count',
                    'status' => 'Status',
                    'actions' => 'Actions',
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                ],
            ],
            'create' => [
                'title' => 'Create New Warranty Package',
                'save-btn' => 'Save Package',
            ],
            'edit' => [
                'title' => 'Edit Warranty Package',
                'update-btn' => 'Update',
            ],
            'fields' => [
                'name' => 'Package Name',
                'duration-months' => 'Duration (Months)',
                'description' => 'Description',
                'price' => 'Price',
                'is-active' => 'Active',
            ],
        ],
    ],

    'frontend' => [
        'search' => [
            'title' => 'Warranty Lookup',
            'description' => 'Enter product serial number or phone number to lookup warranty information',
            'search-placeholder' => 'Enter serial number or phone...',
            'search-btn' => 'Search',
            'no-results' => 'No results found',
            'warranty-details' => 'Warranty Information',
            'remaining-days' => 'Remaining: :days days',
        ],
    ],

    'messages' => [
        'create-success' => 'Warranty created successfully',
        'update-success' => 'Warranty updated successfully',
        'delete-success' => 'Warranty deleted successfully',
        'package-create-success' => 'Warranty package created successfully',
        'package-update-success' => 'Warranty package updated successfully',
        'package-delete-success' => 'Warranty package deleted successfully',
        'package-cannot-delete' => 'Cannot delete package that has warranties',
    ],
];