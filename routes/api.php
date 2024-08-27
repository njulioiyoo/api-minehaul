<?php

declare(strict_types=1);

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\System\AccessController;
use App\Http\Controllers\Api\V1\System\MenuController;
use App\Http\Controllers\Api\V1\System\PermissionController;
use App\Http\Controllers\Api\V1\System\RoleController;
use App\Http\Controllers\Api\V1\System\UserController;
use App\Http\Controllers\Api\V1\Configuration\DeviceController;

Route::middleware('json.api')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:api');
});

Route::middleware('auth:api')->group(function () {
    Route::get('/get-users', function () {
        $users = User::all();
        return response()->json($users);
    });

    Route::post('/api-token', [ApiTokenController::class, 'generateToken']);

    // require base_path('routes/api/configuration.php');
    // require base_path('routes/api/system.php');

    JsonApiRoute::server('v1')->middleware('validate.api')->resources(function (ResourceRegistrar $server) {
        $server->resource('devices', JsonApiController::class);
        $server->resource('roles', JsonApiController::class);
        $server->resource('permissions', JsonApiController::class);
        $server->resource('users', JsonApiController::class);
        $server->resource('menus', JsonApiController::class);

        Route::get('me', [ProfileController::class, 'readProfile']);
        Route::patch('me', [ProfileController::class, 'updateProfile']);

        Route::patch('users/{user}/roles', [AccessController::class, 'updateUserRoles']);
        Route::patch('roles/{role}/permissions', [AccessController::class, 'updateRolePermissions']);

        Route::middleware('verify.user.role')->group(function () {
            Route::prefix('device')->group(function () {
                Route::get('/', [DeviceController::class, 'readDevice'])->name('device.index');
                Route::post('/', [DeviceController::class, 'createDevice'])->name('device.create');
                Route::patch('/', [DeviceController::class, 'updateDevice'])->name('device.update');
                Route::delete('/', [DeviceController::class, 'deleteDevice'])->name('device.delete');
            });

            // Routes for roles
            Route::prefix('role')->group(function () {
                Route::get('/', [RoleController::class, 'readRole'])->name('role.index')->middleware('verify.user.permission:View Roles');
                Route::post('/', [RoleController::class, 'createRole'])->name('role.create')->middleware('verify.user.permission:Create Roles');
                Route::patch('/', [RoleController::class, 'updateRole'])->name('role.update')->middleware('verify.user.permission:Edit Roles');
                Route::delete('/', [RoleController::class, 'deleteRole'])->name('role.delete')->middleware('verify.user.permission:Delete Roles');
            });

            // Routes for permissions
            Route::prefix('permission')->group(function () {
                Route::get('/', [PermissionController::class, 'readPermission'])->name('permission.index')->middleware('verify.user.permission:View Permissions');
                Route::post('/', [PermissionController::class, 'createPermission'])->name('permission.create')->middleware('verify.user.permission:View Permissions');
                Route::patch('/', [PermissionController::class, 'updatePermission'])->name('permission.update')->middleware('verify.user.permission:View Permissions');
                Route::delete('/', [PermissionController::class, 'deletePermission'])->name('permission.delete')->middleware('verify.user.permission:View Permissions');
            });

            // Routes for users
            Route::prefix('user')->group(function () {
                Route::get('/', [UserController::class, 'readUser'])->name('user.index');
                Route::post('/', [UserController::class, 'createUser'])->name('user.create');
                Route::patch('/', [UserController::class, 'updateUser'])->name('user.update');
                Route::delete('/', [UserController::class, 'deleteUser'])->name('user.delete');
            });

            // Routes for menus
            Route::prefix('menu')->group(function () {
                Route::get('/', [MenuController::class, 'index'])->name('menu.index')->middleware('verify.user.permission:View Menus');
                Route::post('/', [MenuController::class, 'createMenu'])->name('menu.create')->middleware('verify.user.permission:View Menus');
                Route::patch('/', [MenuController::class, 'updateMenu'])->name('menu.update')->middleware('verify.user.permission:View Menus');
                Route::delete('/', [MenuController::class, 'deleteMenu'])->name('menu.delete')->middleware('verify.user.permission:View Menus');
            });
        });
    });
});
