<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\VerifyRecaptcha;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);
        $middleware->api(append: [
            VerifyRecaptcha::class
        ])->alias([
            'recaptcha' => VerifyRecaptcha::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
