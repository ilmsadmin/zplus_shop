<?php

// Test script to check warranty route
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Create a test request
$request = \Illuminate\Http\Request::create('/admin/warranty/packages', 'GET');

try {
    $response = $kernel->handle($request);
    echo "Status Code: " . $response->getStatusCode() . PHP_EOL;
    echo "Response Headers:" . PHP_EOL;
    foreach ($response->headers->all() as $key => $values) {
        echo "$key: " . implode(', ', $values) . PHP_EOL;
    }
    if ($response->getStatusCode() === 302) {
        echo "Redirect Location: " . $response->headers->get('Location') . PHP_EOL;
    }
    echo PHP_EOL;
    echo "Response Content (first 500 chars):" . PHP_EOL;
    echo substr($response->getContent(), 0, 500) . PHP_EOL;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    echo "Trace: " . $e->getTraceAsString() . PHP_EOL;
}
