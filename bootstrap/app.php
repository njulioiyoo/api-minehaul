<?php

declare(strict_types=1);

use App\Http\Middleware\JsonApi;
use App\Http\Middleware\ValidateApiToken;
use App\Http\Middleware\VerifyUserPermission;
use App\Http\Middleware\VerifyUserRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'json.api' => JsonApi::class,
            'validate.api' => ValidateApiToken::class,
            'verify.user.role' => VerifyUserRole::class,
            'verify.user.permission' => VerifyUserPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
