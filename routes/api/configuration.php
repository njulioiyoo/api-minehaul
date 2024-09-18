<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Configuration\DeviceController;
use App\Http\Controllers\Api\V1\Configuration\DriverController;
use App\Http\Controllers\Api\V1\Configuration\LocationController;
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
        Route::post('/detail', [DeviceController::class, 'showDevice'])->name('device.show')->middleware('verify.user.permission:show-device');
    });

    // Routes for vehicles
    Route::prefix('vehicles')->group(function () {
        Route::get('/', [VehicleController::class, 'readVehicle'])->name('vehicle.index')->middleware('verify.user.permission:view-vehicle');
        Route::post('/', [VehicleController::class, 'createVehicle'])->name('vehicle.create')->middleware('verify.user.permission:create-vehicle');
        Route::patch('/', [VehicleController::class, 'updateVehicle'])->name('vehicle.update')->middleware('verify.user.permission:edit-vehicle');
        Route::delete('/', [VehicleController::class, 'deleteVehicle'])->name('vehicle.delete')->middleware('verify.user.permission:delete-vehicle');
        Route::post('/detail', [VehicleController::class, 'showVehicle'])->name('vehicle.show')->middleware('verify.user.permission:show-vehicle');
    });

    // Routes for vehicles
    Route::prefix('drivers')->group(function () {
        Route::get('/', [DriverController::class, 'readDriver'])->name('driver.index')->middleware('verify.user.permission:view-driver');
        Route::post('/', [DriverController::class, 'createDriver'])->name('driver.create')->middleware('verify.user.permission:create-driver');
        Route::patch('/', [DriverController::class, 'updateDriver'])->name('driver.update')->middleware('verify.user.permission:edit-driver');
        Route::delete('/', [DriverController::class, 'deleteDriver'])->name('driver.delete')->middleware('verify.user.permission:delete-driver');
        Route::post('/detail', [DriverController::class, 'showDriver'])->name('driver.show')->middleware('verify.user.permission:show-driver');
    });

    // Routes for vehicles
    Route::prefix('locations')->group(function () {
        Route::get('/', [LocationController::class, 'readLocation'])->name('location.index')->middleware('verify.user.permission:view-location');
    });
});
