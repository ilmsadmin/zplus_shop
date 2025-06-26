<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Bootstrap the application
$app->boot();

echo "Testing Warranty Routes:\n";
echo "======================\n\n";

// Test route existence
$routes = \Illuminate\Support\Facades\Route::getRoutes();

$warrantyRoutes = [
    'admin.warranty.index',
    'admin.warranty.packages.index',
    'admin.warranty.packages.create',
    'admin.warranty.packages.store',
    'admin.warranty.packages.show',
    'admin.warranty.packages.edit',
    'admin.warranty.packages.update',
    'admin.warranty.packages.destroy',
];

foreach ($warrantyRoutes as $routeName) {
    try {
        $route = $routes->getByName($routeName);
        if ($route) {
            echo "✓ Route '{$routeName}' found: {$route->uri()}\n";
        } else {
            echo "✗ Route '{$routeName}' NOT found\n";
        }
    } catch (Exception $e) {
        echo "✗ Route '{$routeName}' ERROR: {$e->getMessage()}\n";
    }
}

echo "\n\nTesting Service Provider Registration:\n";
echo "=====================================\n";

// Check if WarrantyServiceProvider is loaded
$providers = $app->getLoadedProviders();
$warrantyProviderLoaded = false;

foreach ($providers as $provider => $loaded) {
    if (strpos($provider, 'WarrantyServiceProvider') !== false) {
        echo "✓ WarrantyServiceProvider loaded: {$provider}\n";
        $warrantyProviderLoaded = true;
    }
}

if (!$warrantyProviderLoaded) {
    echo "✗ WarrantyServiceProvider NOT loaded\n";
}

echo "\n\nTesting Database Connection:\n";
echo "===========================\n";

try {
    // Test warranty packages table
    $packagesCount = \Illuminate\Support\Facades\DB::table('warranty_packages')->count();
    echo "✓ warranty_packages table exists with {$packagesCount} records\n";
    
    // Test warranties table
    $warrantiesCount = \Illuminate\Support\Facades\DB::table('warranties')->count();
    echo "✓ warranties table exists with {$warrantiesCount} records\n";
    
} catch (Exception $e) {
    echo "✗ Database error: {$e->getMessage()}\n";
}

echo "\n\nTesting Menu Configuration:\n";
echo "==========================\n";

try {
    $menuConfig = config('menu');
    if (is_array($menuConfig)) {
        $warrantyMenuFound = false;
        foreach ($menuConfig as $menu) {
            if (isset($menu['key']) && strpos($menu['key'], 'warranty') === 0) {
                echo "✓ Warranty menu found: {$menu['key']} -> {$menu['name']}\n";
                $warrantyMenuFound = true;
            }
        }
        if (!$warrantyMenuFound) {
            echo "✗ No warranty menu items found in config\n";
        }
    } else {
        echo "✗ Menu config not found or invalid\n";
    }
} catch (Exception $e) {
    echo "✗ Menu config error: {$e->getMessage()}\n";
}

echo "\n\nDone!\n";
