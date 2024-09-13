<?php

declare(strict_types=1);

namespace App\Services\Configuration\Vehicle;

use App\Helpers\PaginationHelper;
use App\Models\Vehicle;
use App\Traits\ExceptionHandlerTrait;
use App\Transformers\VehicleTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleService
{
    use ExceptionHandlerTrait;

    protected VehicleTransformer $transformer;

    protected Vehicle $vehicleModel;

    public function __construct(VehicleTransformer $transformer, Vehicle $vehicle)
    {
        $this->transformer = $transformer;
        $this->vehicleModel = $vehicle;
    }

    /**
     * Create a new vehicle in the database and clear related cache.
     *
     * @param  array  $inputData  Input data for creating the vehicle
     * @return mixed Formatted JSON API response
     */
    public function createVehicle(array $inputData)
    {
        return DB::transaction(function () use ($inputData) {
            // Create a new vehicle within a transaction
            $vehicle = $this->vehicleModel->create($inputData);

            // Clear cache for the newly created vehicle
            Cache::forget("vehicle_{$vehicle->uid}");

            // Return formatted JSON API response
            return $this->formatJsonApiResponse(
                $this->transformer->transform($vehicle)
            );
        });
    }

    /**
     * Read a list of vehicles based on query parameters.
     *
     * @param  array  $queryParams  Query parameters for filtering and pagination
     * @return array Paginated and formatted vehicle data
     */
    public function readVehicle(array $queryParams)
    {
        $perPage = $queryParams['page']['size'] ?? 10;
        $page = $queryParams['page']['number'] ?? 1;

        $query = $this->vehicleModel->query();

        // Apply filters if any
        if (isset($queryParams['filter'])) {
            foreach ($queryParams['filter'] as $field => $value) {
                $query->where($field, $value);
            }
        }

        // Get paginated vehicle data
        $vehicles = $query->paginate($perPage, ['*'], 'page[number]', $page);

        // Transform vehicle data using the transformer
        $data = $vehicles->map(fn ($vehicle) => $this->transformer->transform($vehicle))->values()->all();

        // Return paginated data with formatting
        return PaginationHelper::format($vehicles, $data);
    }

    /**
     * Show the details of a vehicle by UID.
     *
     * @param  string  $vehicleUid  UID of the vehicle to be displayed
     * @return mixed Formatted JSON API response
     *
     * @throws ModelNotFoundException If the vehicle is not found
     */
    public function showVehicle(string $vehicleUid)
    {
        // Retrieve vehicle from cache or database
        $vehicle = Cache::remember("vehicle_$vehicleUid", 60, function () use ($vehicleUid) {
            return $this->vehicleModel->where('uid', $vehicleUid)->first();
        });

        // If the vehicle is not found, throw exception
        if (! $vehicle) {
            throw new ModelNotFoundException('Vehicle not found');
        }

        // Return formatted JSON API response
        return $this->formatJsonApiResponse(
            $this->transformer->transform($vehicle)
        );
    }

    /**
     * Update the data of a vehicle by UID.
     *
     * @param  string  $vehicleUid  UID of the vehicle to be updated
     * @param  array  $inputData  Input data for the update
     * @return mixed Formatted JSON API response
     *
     * @throws ModelNotFoundException If the vehicle is not found
     */
    public function updateVehicle(string $vehicleUid, array $inputData)
    {
        return DB::transaction(function () use ($vehicleUid, $inputData) {
            // Update the vehicle data
            $this->vehicleModel->where('uid', $vehicleUid)->update($inputData);

            // Retrieve the updated vehicle
            $vehicle = $this->vehicleModel->where('uid', $vehicleUid)->first();

            // If the vehicle is not found, throw exception
            if ($vehicle) {
                // Update cache with the latest data
                Cache::put("vehicle_$vehicleUid", $vehicle, 60);

                // Return formatted JSON API response
                return $this->formatJsonApiResponse(
                    $this->transformer->transform($vehicle)
                );
            }

            throw new ModelNotFoundException('Vehicle not found');
        });
    }

    /**
     * Delete a vehicle by UID.
     *
     * @param  string  $vehicleUid  UID of the vehicle to be deleted
     * @return mixed JSON response confirming the deletion
     *
     * @throws \Throwable If an error occurs during deletion
     */
    public function deleteVehicle(string $vehicleUid)
    {
        try {
            // Find the vehicle before deleting
            $vehicle = $this->vehicleModel->where('uid', $vehicleUid)->first();

            // If the vehicle is not found, throw exception
            if (! $vehicle) {
                throw new ModelNotFoundException('Vehicle not found');
            }

            // Delete the vehicle
            $vehicle->delete();

            // Forget cache for the deleted vehicle
            Cache::forget("vehicle_$vehicleUid");

            // Return JSON response confirming the deletion
            return response()->json(['message' => 'Vehicle deleted successfully']);
        } catch (\Throwable $th) {
            // Log the error
            Log::error("Error deleting vehicle with UID: {$vehicleUid}, Error: {$th->getMessage()}");
            throw $th;
        }
    }
}
