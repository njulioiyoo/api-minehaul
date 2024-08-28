<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Configuration\DeviceController;
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

JsonApiRoute::server('v1')->middleware('validate.api', 'json.api', 'verify.user.role')->resources(function () {
    // Routes for devices
    Route::prefix('device')->group(function () {
        Route::get('/', [DeviceController::class, 'readDevice'])->name('device.index')->middleware('verify.user.permission:View Device');
        Route::post('/', [DeviceController::class, 'createDevice'])->name('device.create')->middleware('verify.user.permission:Create Device');
        Route::patch('/', [DeviceController::class, 'updateDevice'])->name('device.update')->middleware('verify.user.permission:Edit Device');
        Route::delete('/', [DeviceController::class, 'deleteDevice'])->name('device.delete')->middleware('verify.user.permission:Delete Device');
    });
});
