<?php

// 1. Force the system to use Vercel's ephemeral writable folder
$tmpDir = '/tmp/storage';
foreach (['/logs', '/framework/views', '/framework/cache', '/framework/sessions'] as $path) {
    if (!is_dir($tmpDir . $path)) {
        mkdir($tmpDir . $path, 0755, true);
    }
}

// 2. Overwrite standard Laravel file dependencies completely into memory
$_ENV['APP_CONFIG_CACHE']   = '/tmp/config.php';
$_ENV['APP_ROUTES_CACHE']   = '/tmp/routes.php';
$_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
$_ENV['VIEW_COMPILED_PATH']  = '/tmp/framework/views';

// 3. Forward to the actual framework router
require __DIR__ . '/../public/index.php';
