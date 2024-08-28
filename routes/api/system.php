<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\System\AccessController;
use App\Http\Controllers\Api\V1\System\MenuController;
use App\Http\Controllers\Api\V1\System\PermissionController;
use App\Http\Controllers\Api\V1\System\RoleController;
use App\Http\Controllers\Api\V1\System\UserController;
use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

JsonApiRoute::server('v1')->middleware('json.api')->resources(function () {
    Route::get('me', [ProfileController::class, 'readProfile']);
    Route::patch('me', [ProfileController::class, 'updateProfile']);

    Route::middleware('verify.user.role', 'validate.api')->group(function () {

        Route::patch('users/{user}/roles', [AccessController::class, 'updateUserRoles']);
        Route::patch('roles/{role}/permissions', [AccessController::class, 'updateRolePermissions']);

        // Routes for roles
        Route::prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'readRole'])->name('roles.index')->middleware('verify.user.permission:View Roles');
            Route::post('/', [RoleController::class, 'createRole'])->name('roles.create')->middleware('verify.user.permission:Create Roles');
            Route::patch('/', [RoleController::class, 'updateRole'])->name('roles.update')->middleware('verify.user.permission:Edit Roles');
            Route::delete('/', [RoleController::class, 'deleteRole'])->name('roles.delete')->middleware('verify.user.permission:Delete Roles');
        });

        // Routes for permissions
        Route::prefix('permissions')->group(function () {
            Route::get('/', [PermissionController::class, 'readPermission'])->name('permissions.index')->middleware('verify.user.permission:View Permissions');
            Route::post('/', [PermissionController::class, 'createPermission'])->name('permissions.create')->middleware('verify.user.permission:View Permissions');
            Route::patch('/', [PermissionController::class, 'updatePermission'])->name('permissions.update')->middleware('verify.user.permission:View Permissions');
            Route::delete('/', [PermissionController::class, 'deletePermission'])->name('permissions.delete')->middleware('verify.user.permission:View Permissions');
        });

        // Routes for users
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'readUser'])->name('users.index')->middleware('verify.user.permission:View Users');
            Route::post('/', [UserController::class, 'createUser'])->name('users.create')->middleware('verify.user.permission:Create Users');
            Route::patch('/', [UserController::class, 'updateUser'])->name('users.update')->middleware('verify.user.permission:Edit Users');
            Route::delete('/', [UserController::class, 'deleteUser'])->name('users.delete')->middleware('verify.user.permission:Delete Users');
        });

        // Routes for menus
        Route::prefix('menus')->group(function () {
            Route::get('/', [MenuController::class, 'readMenu'])->name('menus.index')->middleware('verify.user.permission:View Menus');
            Route::post('/', [MenuController::class, 'createMenu'])->name('menus.create')->middleware('verify.user.permission:View Menus');
            Route::patch('/', [MenuController::class, 'updateMenu'])->name('menus.update')->middleware('verify.user.permission:View Menus');
            Route::delete('/', [MenuController::class, 'deleteMenu'])->name('menus.delete')->middleware('verify.user.permission:View Menus');
        });
    });
});
