<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'pwchanged' => \App\Http\Middleware\EnsurePasswordChanged::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\EnsurePasswordChanged::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
        // Stripe webhook must skip CSRF
        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
        ]);
        // Railway runs behind a proxy — trust it so HTTPS/host detection works
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
