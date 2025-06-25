<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Set locale to Vietnamese
app()->setLocale('vi');

echo "Testing GraphQL API Vietnamese translations:\n\n";

// Test some notification translations
$translations = [
    'bagisto_graphql::app.admin.settings.notification.create.new-notification',
    'bagisto_graphql::app.admin.settings.notification.create.back-btn',
    'bagisto_graphql::app.admin.settings.notification.create.title',
    'bagisto_graphql::app.admin.settings.notification.create.content-and-image',
    'bagisto_graphql::app.admin.settings.notification.create.notification-content',
    'bagisto_graphql::app.admin.settings.notification.create.image',
    'bagisto_graphql::app.admin.settings.notification.create.save-btn',
    'bagisto_graphql::app.admin.settings.notification.create.send-btn',
];

foreach ($translations as $key) {
    $translated = trans($key);
    $status = $translated !== $key ? '✓' : '✗';
    echo sprintf("%s %s => %s\n", $status, $key, $translated);
}

echo "\nTesting Admin package translations:\n\n";

$adminTranslations = [
    'admin::app.settings.notification.create.new-notification',
    'admin::app.settings.notification.create.back-btn',
    'admin::app.settings.notification.create.title',
];

foreach ($adminTranslations as $key) {
    $translated = trans($key);
    $status = $translated !== $key ? '✓' : '✗';
    echo sprintf("%s %s => %s\n", $status, $key, $translated);
}
