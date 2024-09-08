<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Configuration\DeviceController;
use App\Http\Controllers\Api\V1\Configuration\VehicleController;
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

Route::middleware(['validate.api', 'json.api', 'verify.user.role'])->group(function () {
    // Routes for devices
    Route::prefix('devices')->group(function () {
        Route::get('/', [DeviceController::class, 'readDevice'])->name('device.index')->middleware('verify.user.permission:view-device');
        Route::post('/', [DeviceController::class, 'createDevice'])->name('device.create')->middleware('verify.user.permission:create-device');
        Route::patch('/', [DeviceController::class, 'updateDevice'])->name('device.update')->middleware('verify.user.permission:edit-device');
        Route::delete('/', [DeviceController::class, 'deleteDevice'])->name('device.delete')->middleware('verify.user.permission:delete-device');
        Route::post('/', [DeviceController::class, 'showDevice'])->name('device.show');
    });

    // Routes for vehicles
    Route::prefix('vehicles')->group(function () {
        Route::get('/', [VehicleController::class, 'readVehicle'])->name('vehicle.index')->middleware('verify.user.permission:view-vehicle');
        Route::post('/', [VehicleController::class, 'createVehicle'])->name('vehicle.create')->middleware('verify.user.permission:create-vehicle');
        Route::patch('/', [VehicleController::class, 'updateVehicle'])->name('vehicle.update')->middleware('verify.user.permission:edit-vehicle');
        Route::delete('/', [VehicleController::class, 'deleteVehicle'])->name('vehicle.delete')->middleware('verify.user.permission:delete-vehicle');
        Route::post('/', [VehicleController::class, 'showVehicle'])->name('vehicle.show');
    });
});
