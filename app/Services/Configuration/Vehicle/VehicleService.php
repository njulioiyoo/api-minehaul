<?php

declare(strict_types=1);

namespace App\Services\Configuration\Vehicle;

use App\Models\Vehicle;
use App\Services\Configuration\EntityCrudService;
use App\Traits\ExceptionHandlerTrait;
use App\Transformers\VehicleTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VehicleService
{
    use ExceptionHandlerTrait;

    protected EntityCrudService $entityCrudService;

    protected VehicleTransformer $transformer;

    protected Vehicle $vehicleModel;

    public function __construct(
        EntityCrudService $entityCrudService,
        VehicleTransformer $transformer,
        Vehicle $vehicle
    ) {
        $this->entityCrudService = $entityCrudService;
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
        return $this->entityCrudService->create(
            $this->vehicleModel,
            $inputData,
            'vehicle',  // Cache key prefix for vehicles
            $this->transformer
        );
    }

    /**
     * Read a list of vehicles based on query parameters.
     *
     * @param  array  $queryParams  Query parameters for filtering and pagination
     * @return array Paginated and formatted vehicle data
     */
    public function readVehicle(array $queryParams)
    {
        // Call the generic read method from EntityCrudService (no relations needed)
        return $this->entityCrudService->read(
            $this->vehicleModel,    // The model instance (Vehicle)
            $queryParams,           // The query parameters
            $this->transformer      // The transformer for Vehicle
        );
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
        // Use the generic show method from EntityCrudService for vehicle details
        return $this->entityCrudService->show(
            $this->vehicleModel,    // The model instance (Vehicle)
            $vehicleUid,            // The vehicle UID to be fetched
            'vehicle',              // Cache key prefix for vehicles
            $this->transformer      // The transformer for Vehicle
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
        return $this->entityCrudService->update(
            $this->vehicleModel,
            $vehicleUid,
            $inputData,
            'vehicle',  // Cache key prefix for vehicles
            $this->transformer
        );
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
        return $this->entityCrudService->delete(
            $this->vehicleModel,
            $vehicleUid,
            'vehicle'  // Cache key prefix for vehicles
        );
    }
}
