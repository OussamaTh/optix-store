<?php

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Str;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'customer' => \App\Http\Middleware\EnsureUserIsCustomer::class,
        ]);

        $middleware->trustProxies(at: '*');
    })


    ->withExceptions(function (Exceptions $exceptions) {

        // 💡 GLOBAL NULL-PROPERTY SHIELD FOR PRODUCTION
        $exceptions->render(function (\ErrorException $e) {
            if (config('app.env') === 'production' && Str::contains($e->getMessage(), 'Attempt to read property')) {
                // Log it silently so you know what broke behind the scenes
                logger()->error('Global Null Shield Intercepted: ' . $e->getMessage());

                // Return an empty response, redirect back, or render a clean fallback
                return response()->view('errors.safe-fallback', [], 200);
            }
        });
    })->create();
