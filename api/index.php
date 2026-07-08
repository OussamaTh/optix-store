<?php

// Force Laravel to use Vercel's ephemeral writable storage
$tmpDir = '/tmp/storage';
foreach (['/logs', '/framework/views', '/framework/cache', '/framework/sessions'] as $path) {
    if (!is_dir($tmpDir . $path)) {
        mkdir($tmpDir . $path, 0755, true);
    }
}

$_ENV['APP_CONFIG_CACHE']    = '/tmp/config.php';
$_ENV['APP_ROUTES_CACHE']    = '/tmp/routes.php';
$_ENV['APP_SERVICES_CACHE']  = '/tmp/services.php';
$_ENV['APP_PACKAGES_CACHE']  = '/tmp/packages.php';
$_ENV['VIEW_COMPILED_PATH']  = '/tmp/framework/views';

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php'; // boot ONCE

// Run pending migrations, at most once per warm container
if (!file_exists('/tmp/migrated.txt')) {
    try {
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->call('migrate', ['--force' => true]);
        file_put_contents('/tmp/migrated.txt', 'done');
    } catch (\Throwable $e) {
        error_log('Vercel Migration Failed: ' . $e->getMessage());
    }
}

// Handle the request with the SAME $app — no second boot
$app->handleRequest(Illuminate\Http\Request::capture());