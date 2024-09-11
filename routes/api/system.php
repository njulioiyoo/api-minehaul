<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\System\MenuController;
use App\Http\Controllers\Api\V1\System\PermissionController;
use App\Http\Controllers\Api\V1\System\RoleController;
use App\Http\Controllers\Api\V1\System\UserController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['json.api'])->group(function () {
    Route::middleware(['verify.user.role', 'validate.api'])->group(function () {
        // Routes for roles
        Route::prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'readRole'])->name('roles.index')->middleware('verify.user.permission:view-roles');
            Route::post('/', [RoleController::class, 'createRole'])->name('roles.create')->middleware('verify.user.permission:create-roles');
            Route::patch('/', [RoleController::class, 'updateRole'])->name('roles.update')->middleware('verify.user.permission:edit-roles');
            Route::delete('/', [RoleController::class, 'deleteRole'])->name('roles.delete')->middleware('verify.user.permission:delete-roles');
            Route::post('/detail', [RoleController::class, 'showRole'])->name('roles.show')->middleware('verify.user.permission:show-roles');
        });

        // Routes for permissions
        Route::prefix('permissions')->group(function () {
            Route::get('/', [PermissionController::class, 'readPermission'])->name('permissions.index')->middleware('verify.user.permission:view-permissions');
            Route::post('/', [PermissionController::class, 'createPermission'])->name('permissions.create')->middleware('verify.user.permission:create-permissions');
            Route::patch('/', [PermissionController::class, 'updatePermission'])->name('permissions.update')->middleware('verify.user.permission:edit-permissions');
            Route::delete('/', [PermissionController::class, 'deletePermission'])->name('permissions.delete')->middleware('verify.user.permission:delete-permissions');
        });

        // Routes for users
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'readUser'])->name('users.index')->middleware('verify.user.permission:view-users');
            Route::post('/', [UserController::class, 'createUser'])->name('users.create')->middleware('verify.user.permission:create-users');
            Route::patch('/', [UserController::class, 'updateUser'])->name('users.update')->middleware('verify.user.permission:edit-users');
            Route::delete('/', [UserController::class, 'deleteUser'])->name('users.delete')->middleware('verify.user.permission:delete-users');
            Route::post('/detail', [UserController::class, 'showUser'])->name('users.show')->middleware('verify.user.permission:show-users');
        });

        // Routes for menus
        Route::prefix('menus')->group(function () {
            Route::get('/', [MenuController::class, 'readMenu'])->name('menus.index')->middleware('verify.user.permission:view-menus');
            Route::post('/', [MenuController::class, 'createMenu'])->name('menus.create')->middleware('verify.user.permission:create-menus');
            Route::patch('/', [MenuController::class, 'updateMenu'])->name('menus.update')->middleware('verify.user.permission:edit-menus');
            Route::delete('/', [MenuController::class, 'deleteMenu'])->name('menus.delete')->middleware('verify.user.permission:delete-menus');
        });
    });
});
