<?php

return [
    'title' => 'WooCommerce to Bagisto Converter',
    'description' => 'Convert WooCommerce product CSV files to Bagisto format and optionally import them directly.',
    
    'upload' => [
        'title' => 'Upload WooCommerce CSV File',
        'description' => 'Drag and drop your CSV file here or click to select',
        'select_file' => 'Select CSV File',
    ],
    
    'selected_file' => 'Selected File',
    
    'stats' => [
        'total_rows' => 'Total Rows',
        'convertible' => 'Convertible Products',
        'conversion_rate' => 'Conversion Rate',
    ],
    
    'action' => [
        'title' => 'Choose an Action',
        'download' => [
            'title' => 'Download Converted CSV',
            'description' => 'Download the converted Bagisto CSV file to your computer',
            'button' => 'Download CSV',
        ],
        'import' => [
            'title' => 'Import to Bagisto',
            'description' => 'Import the converted products directly into your Bagisto store',
            'button' => 'Import Products',
        ],
    ],
    
    'messages' => [
        'conversion_success' => 'Conversion completed successfully!',
        'import_success' => 'Products imported successfully!',
        'no_valid_data' => 'No valid data found in the CSV file.',
        'file_required' => 'Please select a CSV file.',
        'invalid_file_type' => 'Please select a valid CSV file.',
    ],
];