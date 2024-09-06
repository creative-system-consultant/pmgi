<?php

use App\Http\Middleware\CheckUserAccess;
use App\Http\Middleware\CheckUserRole;
use App\Http\Middleware\EnsureHasSession;
use App\Http\Middleware\RestrictDuringSession;
use App\Http\Middleware\RestrictLoadingAccess;
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
            'check.role' => CheckUserRole::class,
            'check.access' => CheckUserAccess::class,
            'restrict.session' => RestrictDuringSession::class,
            'restrict.loading' => RestrictLoadingAccess::class,
            'ensure.session' => EnsureHasSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
