<?php

declare(strict_types=1);

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use App\Http\Controllers\ReferenceModuleController;
use App\Http\Controllers\Api\V1\ProfileController;


Route::middleware('json.api')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:api');
});

Route::middleware('auth:api')->group(function () {
    Route::post('/api-token', [ApiTokenController::class, 'generateToken']);

    JsonApiRoute::server('v1')->middleware('json.api', 'validate.api')->resources(function () {
        Route::get('me', [ProfileController::class, 'readProfile']);
        Route::patch('me', [ProfileController::class, 'updateProfile']);

        Route::get('/reference', [ReferenceModuleController::class, 'readReference'])->middleware('validate.api');
    });

    require base_path('routes/api/configuration.php');
    require base_path('routes/api/system.php');
});
