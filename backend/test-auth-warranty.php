<?php

// Test script với authentication
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Kiểm tra admin user có tồn tại không
try {
    $admin = \Webkul\User\Models\Admin::first();
    if ($admin) {
        echo "Found admin user: " . $admin->email . PHP_EOL;
        
        // Tạo authenticated request
        $request = \Illuminate\Http\Request::create('/admin/warranty/packages', 'GET');
        
        // Mock authentication
        \Illuminate\Support\Facades\Auth::guard('admin')->login($admin);
        
        $response = $kernel->handle($request);
        echo "Status Code: " . $response->getStatusCode() . PHP_EOL;
        
        if ($response->getStatusCode() === 302) {
            echo "Redirect Location: " . $response->headers->get('Location') . PHP_EOL;
        } elseif ($response->getStatusCode() === 200) {
            echo "Success! Page loaded correctly." . PHP_EOL;
            echo "Content preview (first 200 chars):" . PHP_EOL;
            echo substr(strip_tags($response->getContent()), 0, 200) . PHP_EOL;
        } else {
            echo "Unexpected status code." . PHP_EOL;
            echo "Response content (first 500 chars):" . PHP_EOL;
            echo substr($response->getContent(), 0, 500) . PHP_EOL;
        }
        
    } else {
        echo "No admin users found in database!" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    echo "File: " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
}
