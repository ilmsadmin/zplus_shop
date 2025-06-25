<?php

require_once '/Volumes/DATA/project/zplus_shop/backend/bootstrap/app.php';

$app = app();

// Set the locale to Vietnamese
app()->setLocale('vi');

// Test the specific translation keys that were requested
$translationKeys = [
    'bagisto_graphql::app.admin.settings.notification.create.new-notification',
    'bagisto_graphql::app.admin.settings.notification.create.back-btn',
    'bagisto_graphql::app.admin.settings.notification.create.title',
    'bagisto_graphql::app.admin.settings.notification.create.content-and-image',
    'bagisto_graphql::app.admin.settings.notification.create.notification-content',
    'bagisto_graphql::app.admin.settings.notification.create.image',
];

echo "Testing Vietnamese translations for GraphQL API package:\n";
echo "=======================================================\n\n";

foreach ($translationKeys as $key) {
    $translation = trans($key);
    $status = ($translation !== $key) ? '✅ FOUND' : '❌ MISSING';
    echo sprintf("%-70s %s: %s\n", $key, $status, $translation);
}

echo "\n\nAdditional notification translations:\n";
echo "====================================\n\n";

$additionalKeys = [
    'bagisto_graphql::app.admin.settings.notification.index.title',
    'bagisto_graphql::app.admin.settings.notification.edit.edit-notification',
    'bagisto_graphql::app.admin.settings.notification.create.create-btn-title',
];

foreach ($additionalKeys as $key) {
    $translation = trans($key);
    $status = ($translation !== $key) ? '✅ FOUND' : '❌ MISSING';
    echo sprintf("%-70s %s: %s\n", $key, $status, $translation);
}
