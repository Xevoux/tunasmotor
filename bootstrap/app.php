<?php

use App\Http\Middleware\EnsureAdminRole;
use App\Http\Middleware\EnsureCustomerRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust all proxies for shared hosting environments (Rumahweb, etc.)
        // This helps with HTTPS detection behind load balancers/proxies
        $middleware->trustProxies(at: '*');

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
        // Handle 419 Page Expired error gracefully
        // Redirect to login page with a friendly message instead of showing error
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() === 419) {
                // If it's an AJAX/API request, return JSON response
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Session expired. Please refresh the page and try again.',
                        'redirect' => route('login'),
                    ], 419);
                }
                
                // For web requests, redirect to login with message
                return redirect()->route('login')
                    ->with('error', 'Session Anda telah kedaluwarsa. Silakan login kembali.');
            }
        });
    })->create();
