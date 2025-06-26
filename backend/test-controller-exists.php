<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Tạo một request giả lập để test controller
$request = \Illuminate\Http\Request::create('/admin/warranty/packages', 'GET');

try {
    // Lấy route
    $route = \Illuminate\Support\Facades\Route::getRoutes()->match($request);
    echo "Route matched: " . $route->getName() . "\n";
    echo "URI: " . $route->uri() . "\n";
    echo "Action: " . $route->getActionName() . "\n";
    echo "Controller: " . $route->getController() . "\n";
    
    // Kiểm tra controller class có tồn tại không
    $controllerClass = explode('@', $route->getActionName())[0];
    if (class_exists($controllerClass)) {
        echo "✓ Controller class exists: {$controllerClass}\n";
        
        // Kiểm tra method có tồn tại không
        $method = explode('@', $route->getActionName())[1];
        if (method_exists($controllerClass, $method)) {
            echo "✓ Controller method exists: {$method}\n";
        } else {
            echo "✗ Controller method missing: {$method}\n";
        }
    } else {
        echo "✗ Controller class missing: {$controllerClass}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
