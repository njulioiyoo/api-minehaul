<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Configuration\DeviceController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\System\AccessController;
use App\Http\Controllers\Api\V1\System\MenuController;
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
    Route::get('device', [DeviceController::class, 'readDevice'])->name('device.index')->middleware('verify.user.role');
    Route::post('device', [DeviceController::class, 'createDevice'])->name('device.create')->middleware('verify.user.role');
    Route::patch('device', [DeviceController::class, 'updateDevice'])->name('device.update')->middleware('verify.user.role');
    Route::delete('device', [DeviceController::class, 'deleteDevice'])->name('device.delete')->middleware('verify.user.role');

    $server->resource('permissions', JsonApiController::class);
    Route::get('permission', [PermissionController::class, 'readPermission'])->name('permission.index')->middleware('verify.user.role');
    Route::post('permission', [PermissionController::class, 'createPermission'])->name('permission.create')->middleware('verify.user.role');
    Route::patch('permission', [PermissionController::class, 'updatePermission'])->name('permission.update')->middleware('verify.user.role');
    Route::delete('permission', [PermissionController::class, 'deletePermission'])->name('permission.delete')->middleware('verify.user.role');

    $server->resource('roles', JsonApiController::class);
    Route::get('role', [RoleController::class, 'readRole'])->name('role.index')->middleware('verify.user.role');
    Route::post('role', [RoleController::class, 'createRole'])->name('role.create')->middleware('verify.user.role');
    Route::patch('role', [RoleController::class, 'updateRole'])->name('role.update')->middleware('verify.user.role');
    Route::delete('role', [RoleController::class, 'deleteRole'])->name('role.delete')->middleware('verify.user.role');

    Route::patch('users/{user}/roles', [AccessController::class, 'updateUserRoles']);
    Route::patch('roles/{role}/permissions', [AccessController::class, 'updateRolePermissions']);

    $server->resource('users', JsonApiController::class);
    Route::get('user', [UserController::class, 'readUser'])->name('user.index')->middleware('verify.user.role');
    Route::post('user', [UserController::class, 'createUser'])->name('user.create')->middleware('verify.user.role');
    Route::patch('user', [UserController::class, 'updateUser'])->name('user.update')->middleware('verify.user.role');
    Route::delete('user', [UserController::class, 'deleteUser'])->name('user.delete')->middleware('verify.user.role');

    $server->resource('menus', JsonApiController::class);
    Route::get('menu', [MenuController::class, 'index'])->name('menu.index')->middleware('verify.user.role');
    Route::post('menu', [MenuController::class, 'createMenu'])->name('menu.create')->middleware('verify.user.role');
    Route::patch('menu', [MenuController::class, 'updateMenu'])->name('menu.update')->middleware('verify.user.role');
    Route::delete('menu', [MenuController::class, 'deleteMenu'])->name('menu.delete')->middleware('verify.user.role');
});
