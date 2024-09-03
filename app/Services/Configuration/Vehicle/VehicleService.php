<?php

declare(strict_types=1);

namespace App\Services\Configuration\Vehicle;

use App\Helpers\PaginationHelper;
use App\Models\Vehicle;
use App\Transformers\VehicleTransformer;
use Illuminate\Support\Facades\DB;

class VehicleService
{
    protected $transformer;

    public function __construct(VehicleTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function createVehicle(array $inputData)
    {
        DB::beginTransaction();
        $vehicle = Vehicle::create($inputData);

        if (!$vehicle) {
            DB::rollBack();
            throw new \Exception('Failed to create vehicle');
        }

        $vehicle = $this->transformer->transform($vehicle);

        DB::commit();

        return $vehicle;
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

        $devices = $query->paginate($perPage, ['*'], 'page[number]', $page);

        $data = $devices->map(function ($device) {
            return $this->transformer->transform($device);
        })->values()->all(); // Convert to array

        return PaginationHelper::format($devices, $data);
    }
}
