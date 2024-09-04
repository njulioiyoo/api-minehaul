<?php

declare(strict_types=1);

namespace App\Services\Configuration\Vehicle;

use App\Helpers\PaginationHelper;
use App\Models\Vehicle;
use App\Transformers\VehicleTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleService
{
    protected $transformer;

    public function __construct(VehicleTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function createVehicle(array $inputData)
    {
        return DB::transaction(function () use ($inputData) {
            $vehicle = Vehicle::create($inputData);

            return $this->transformer->transform($vehicle);
        });
    }

    public function readVehicle(array $queryParams)
    {
        $perPage = $queryParams['page']['size'] ?? 10;
        $page = $queryParams['page']['number'] ?? 1;

        $query = Vehicle::query();

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

    public function updateVehicle(string $vehicleId, array $inputData)
    {
        return DB::transaction(function () use ($vehicleId, $inputData) {
            $vehicle = Vehicle::findOrFail($vehicleId);

            $vehicle->update($inputData);

            return $this->transformer->transform($vehicle);
        });
    }

    public function deleteVehicle($vehicleId)
    {
        try {
            $vehicle = Vehicle::findOrFail($vehicleId);

            $vehicle->delete();
        } catch (\Throwable $th) {
            Log::error("Error deleting vehicle with ID: {$vehicleId}, Error: {$th->getMessage()}");
            throw $th;
        }
    }
}
