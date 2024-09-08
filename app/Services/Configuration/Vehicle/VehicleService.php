<?php

declare(strict_types=1);

namespace App\Services\Configuration\Vehicle;

use App\Helpers\PaginationHelper;
use App\Models\Vehicle;
use App\Transformers\VehicleTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class VehicleService
{
    protected $transformer;
    protected $vehicleModel;

    public function __construct(VehicleTransformer $transformer, Vehicle $vehicle)
    {
        $this->transformer = $transformer;
        $this->vehicleModel = $vehicle;
    }

    public function createVehicle(array $inputData)
    {
        return DB::transaction(function () use ($inputData) {
            $vehicle = $this->vehicleModel->create($inputData);

            // Clear cache setelah membuat kendaraan baru
            Cache::forget('vehicle_' . $vehicle->id);

            return $this->transformer->transform($vehicle);
        });
    }

    public function readVehicle(array $queryParams)
    {
        $perPage = $queryParams['page']['size'] ?? 10;
        $page = $queryParams['page']['number'] ?? 1;

        $query = $this->vehicleModel->query();

        if (isset($queryParams['filter'])) {
            foreach ($queryParams['filter'] as $field => $value) {
                $query->where($field, $value);
            }
        }

        $vehicle = $query->paginate($perPage, ['*'], 'page[number]', $page);

        $data = $vehicle->map(function ($vehicle) {
            return $this->transformer->transform($vehicle);
        })->values()->all(); // Convert to array

        return PaginationHelper::format($vehicle, $data);
    }

    public function showVehicle(string $vehicleUid)
    {
        // Cache vehicle untuk 60 menit
        $vehicle = Cache::remember("vehicle_$vehicleUid", 60, function () use ($vehicleUid) {
            return $this->vehicleModel->findOrFail($vehicleUid);
        });

        return $this->transformer->transform($vehicle);
    }

    public function updateVehicle(string $vehicleId, array $inputData)
    {
        return DB::transaction(function () use ($vehicleId, $inputData) {
            $vehicle = Cache::remember("vehicle_$vehicleId", 60, function () use ($vehicleId) {
                return $this->vehicleModel->findOrFail($vehicleId);
            });

            $vehicle->update($inputData);

            // Update cache setelah update vehicle
            Cache::put("vehicle_$vehicleId", $vehicle, 60);

            return $this->transformer->transform($vehicle);
        });
    }

    public function deleteVehicle($vehicleId)
    {
        try {
            $vehicle = Cache::remember("vehicle_$vehicleId", 60, function () use ($vehicleId) {
                return $this->vehicleModel->findOrFail($vehicleId);
            });

            $vehicle->delete();

            // Clear cache setelah delete
            Cache::forget("vehicle_$vehicleId");
        } catch (\Throwable $th) {
            Log::error("Error deleting vehicle with ID: {$vehicleId}, Error: {$th->getMessage()}");
            throw $th;
        }
    }
}
