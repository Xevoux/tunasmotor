<?php

use App\Http\Middleware\EnsureAdminRole;
use App\Http\Middleware\EnsureCustomerRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Exclude Midtrans callback from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'payment/callback',
            'midtrans/callback', // Webhook from Midtrans
        ]);

        // Register middleware aliases
        $middleware->alias([
            'customer' => EnsureCustomerRole::class,
            'admin' => EnsureAdminRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
