<?php
// Debug script
echo "Testing warranty packages route..." . PHP_EOL;

// Test direct URL generation
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

try {
    // Test if route can be generated
    $url = route('admin.warranty.packages.index');
    echo "Generated URL: " . $url . PHP_EOL;
    
    // Test if controller class exists
    $controller = new \Zplus\Warranty\Http\Controllers\Admin\WarrantyPackageController();
    echo "Controller exists: YES" . PHP_EOL;
    
    // Test if view file exists
    $viewPath = resource_path('themes/admin/default/views/warranty/admin/packages/index.blade.php');
    if (!file_exists($viewPath)) {
        $viewPath = base_path('packages/Zplus/Warranty/src/Resources/views/admin/packages/index.blade.php');
    }
    echo "View file exists: " . (file_exists($viewPath) ? 'YES' : 'NO') . PHP_EOL;
    echo "View path: " . $viewPath . PHP_EOL;
    
    // Test warranty package model
    $count = \Zplus\Warranty\Models\WarrantyPackage::count();
    echo "Warranty packages in DB: " . $count . PHP_EOL;
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    echo "File: " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
}
