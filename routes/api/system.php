<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\System\MenuController;
use App\Http\Controllers\Api\V1\System\RoleController;
use App\Http\Controllers\Api\V1\System\UserController;
use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
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

JsonApiRoute::server('v1')->middleware('validate.api')->resources(function (ResourceRegistrar $server) {
    $server->resource('roles', JsonApiController::class);
    $server->resource('menus', JsonApiController::class);
    $server->resource('users', JsonApiController::class);

    Route::middleware('verify.user.role')->group(function () {
        // Routes for roles
        Route::prefix('role')->group(function () {
            Route::get('/', [RoleController::class, 'readRole'])->name('role.index')->middleware('verify.user.permission:View Roles');
            Route::post('/', [RoleController::class, 'createRole'])->name('role.create')->middleware('verify.user.permission:Create Roles');
            Route::patch('/', [RoleController::class, 'updateRole'])->name('role.update')->middleware('verify.user.permission:Edit Roles');
            Route::delete('/', [RoleController::class, 'deleteRole'])->name('role.delete')->middleware('verify.user.permission:Delete Roles');
        });

        // Routes for users
        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'readUser'])->name('user.index')->middleware('verify.user.permission:View Users');
            Route::post('/', [UserController::class, 'createUser'])->name('user.create')->middleware('verify.user.permission:Create Users');
            Route::patch('/', [UserController::class, 'updateUser'])->name('user.update')->middleware('verify.user.permission:Edit Users');
            Route::delete('/', [UserController::class, 'deleteUser'])->name('user.delete')->middleware('verify.user.permission:Delete Users');
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
