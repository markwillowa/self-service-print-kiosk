<?php

use App\Http\Middleware\EnsureAdminAuthenticated;
use App\Http\Middleware\EnsureKioskRegistered;
use App\Http\Middleware\RestrictKioskAccess;
use App\Http\Middleware\SuperAdminMiddleware;
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
        $middleware->alias([
            'kiosk.registered' => EnsureKioskRegistered::class,
            'admin.auth' => EnsureAdminAuthenticated::class,
            'super.admin' => SuperAdminMiddleware::class,
            'kiosk.local' => RestrictKioskAccess::class,
        ]);

        $middleware->validateCsrfTokens(
            except: [
                'coin',
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
