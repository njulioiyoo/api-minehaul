<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExternalApiController;
use App\Http\Controllers\ReferenceModuleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::middleware('json.api')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/oauth/refresh', [AuthController::class, 'refreshToken'])->name('oauth.refresh');
});

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/api-token', [ApiTokenController::class, 'generateToken'])->middleware('json.api');

    Route::prefix('wls')->group(function () {
        Route::get('/trip-load-scanners', [ExternalApiController::class, 'tripLoadScanners']);
        Route::get('/sync-load-scanners', [ExternalApiController::class, 'syncTickets']);
    });

    Route::middleware(['json.api', 'validate.api'])->group(function () {
        Route::get('me', [ProfileController::class, 'readProfile'])->name('readProfile');
        Route::patch('me', [ProfileController::class, 'updateProfile']);

        Route::prefix('reference')->group(function () {
            Route::post('/devices', [ReferenceModuleController::class, 'getDeviceData'])->name('getDeviceData');
            Route::post('/vehicles', [ReferenceModuleController::class, 'getVehicleData'])->name('getVehicleData');
            Route::post('/locations', [ReferenceModuleController::class, 'getLocationData'])->name('getLocationData');
        });

        Route::prefix('dashboard')->group(function () {
            Route::get('trips', [DashboardController::class, 'readTrip'])->name('readTrip');
            Route::get('production', [DashboardController::class, 'readProduction'])->name('readProduction');
        });
    });

    require base_path('routes/api/configuration.php');
    require base_path('routes/api/system.php');
});

Route::post('send-data-load-scanner', function (Request $request) {
    DB::table('scanner_logs')->insert([
        'headers' => json_encode($request->headers->all()),
        'url' => $request->fullUrl(),
        'ip' => $request->ip(),
        'body' => $request->getContent(),
        'user_agent' => $request->header('User-Agent'),
        'method' => $request->method(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json(['message' => 'Data received and stored'], 200);
});
