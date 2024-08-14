<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Configuration\DeviceController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\System\AccessController;
use App\Http\Controllers\Api\V1\System\PermissionController;
use App\Http\Controllers\Api\V1\System\RoleController;
use App\Http\Controllers\Api\V1\System\UserController;
use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

Route::middleware('json.api')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:api');
});

Route::middleware('auth:api')->group(function () {
    Route::post('/api-token', [ApiTokenController::class, 'generateToken']);
});

JsonApiRoute::server('v1')->middleware('validate.api')->resources(function (ResourceRegistrar $server) {
    $server->resource('users', JsonApiController::class);
    Route::get('me', [ProfileController::class, 'readProfile']);
    Route::patch('me', [ProfileController::class, 'updateProfile']);

    $server->resource('devices', JsonApiController::class);
    Route::get('device', [DeviceController::class, 'readDevice']);
    Route::post('device', [DeviceController::class, 'createDevice']);
    Route::patch('device', [DeviceController::class, 'updateDevice']);
    Route::delete('device', [DeviceController::class, 'deleteDevice']);

    Route::middleware(['verify.user.role:Administrator'])->group(function () use ($server) {
        // Resource route with JSON:API controller
        $server->resource('permissions', JsonApiController::class);

        // Routes requiring the same middleware
        Route::get('permission', [PermissionController::class, 'readPermission']);
        Route::post('permission', [PermissionController::class, 'createPermission']);
        Route::patch('permission', [PermissionController::class, 'updatePermission']);
        Route::delete('permission', [PermissionController::class, 'deletePermission']);
    });

    $server->resource('roles', JsonApiController::class);
    Route::get('role', [RoleController::class, 'readRole']);
    Route::post('role', [RoleController::class, 'createRole']);
    Route::patch('role', [RoleController::class, 'updateRole']);
    Route::delete('role', [RoleController::class, 'deleteRole']);

    Route::patch('users/{user}/roles', [AccessController::class, 'updateUserRoles']);
    Route::patch('roles/{role}/permissions', [AccessController::class, 'updateRolePermissions']);

    Route::middleware(['verify.user.role:Administrator'])->group(function () use ($server) {
        // Resource route with JSON:API controller
        $server->resource('users', JsonApiController::class);

        // Routes requiring the same middleware
        Route::get('user', [UserController::class, 'readUser']);
        Route::post('user', [UserController::class, 'createUser']);
        Route::patch('user', [UserController::class, 'updateUser']);
        Route::delete('user', [UserController::class, 'deleteUser']);
    });
});
