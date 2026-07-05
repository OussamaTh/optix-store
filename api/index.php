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

// 3. AUTOMATIC MIGRATIONS RUNNER
// This checks if a special marker file exists in /tmp. If not, it triggers migrations.
if (!file_exists('/tmp/migrated.txt')) {
    try {
        require __DIR__ . '/../vendor/autoload.php';
        $app = require_once __DIR__ . '/../bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

        // Execute the migration command quietly behind the scenes
        $kernel->call('migrate', ['--force' => true]);

        file_put_contents('/tmp/migrated.txt', 'done');
    } catch (\Exception $e) {
        // Log the error silently to Vercel logs so it doesn't break the response page load
        error_log('Vercel Migration Failed: ' . $e->getMessage());
    }
}

// 4. Forward to the actual framework router
require __DIR__ . '/../public/index.php';
