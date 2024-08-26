<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\System\AccessController;
use App\Http\Controllers\Api\V1\System\PermissionController;
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

    require base_path('routes/api/configuration.php');
    require base_path('routes/api/system.php');
});

JsonApiRoute::server('v1')->middleware('validate.api')->resources(function (ResourceRegistrar $server) {
    Route::get('me', [ProfileController::class, 'readProfile']);
    Route::patch('me', [ProfileController::class, 'updateProfile']);

    $server->resource('permissions', JsonApiController::class);
    Route::get('permission', [PermissionController::class, 'readPermission'])->name('permission.index')->middleware('verify.user.role');
    Route::post('permission', [PermissionController::class, 'createPermission'])->name('permission.create');
    Route::patch('permission', [PermissionController::class, 'updatePermission'])->name('permission.update');
    Route::delete('permission', [PermissionController::class, 'deletePermission'])->name('permission.delete');

    Route::patch('users/{user}/roles', [AccessController::class, 'updateUserRoles']);
    Route::patch('roles/{role}/permissions', [AccessController::class, 'updateRolePermissions']);
});
