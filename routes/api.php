<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Configuration\DeviceController;
use App\Http\Controllers\Api\V1\ProfileController;
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
});
