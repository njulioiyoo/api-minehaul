<?php

declare(strict_types=1);

namespace App\Services\Configuration\Driver;

use App\Models\Driver;
use App\Services\Configuration\EntityCrudService;
use App\Traits\ExceptionHandlerTrait;
use App\Transformers\DriverTransformer;

class DriverService
{
    use ExceptionHandlerTrait;

    protected EntityCrudService $entityCrudService;

    protected DriverTransformer $transformer;

    protected Driver $driverModel;

    public function __construct(EntityCrudService $entityCrudService, DriverTransformer $transformer, Driver $driver)
    {
        $this->entityCrudService = $entityCrudService;
        $this->transformer = $transformer;
        $this->driverModel = $driver;
    }

    /**
     * Create a new driver in the database and clear related cache.
     *
     * @param  array  $inputData  Input data for creating the driver
     * @return mixed Formatted JSON API response
     */
    public function createDriver(array $inputData)
    {
        // Call the generic create method from EntityCrudService
        return $this->entityCrudService->create(
            $this->driverModel,             // Model
            $inputData,                     // Input data
            'driver',                        // Cache key prefix
            $this->transformer               // Transformer
        );
    }

    /**
     * Read drivers with pagination and filters.
     *
     * @param  array  $queryParams  Query parameters including filters and pagination info.
     * @return array The paginated data with formatted response.
     */
    public function readDriver(array $queryParams): array
    {
        // Define the relationships for the Driver model
        $relations = ['account', 'pit'];

        // Call the generic read method from EntityCrudService
        return $this->entityCrudService->read(
            $this->driverModel,            // The model instance (Driver)
            $queryParams,                  // The query parameters
            $this->transformer,            // The transformer for Driver
            $relations                     // Relationships to eager load
        );
    }

    /**
     * Show a single driver based on UID.
     *
     * @param  string  $driverUid  The UID of the driver to retrieve
     * @return array The formatted response after retrieval
     */
    public function showDriver(string $driverUid): array
    {
        return $this->entityCrudService->show(
            $this->driverModel,          // The model instance (Driver)
            $driverUid,                  // The driver UID
            'driver',                    // Cache key prefix for driver
            $this->transformer           // The transformer for Driver
        );
    }

    /**
     * Update a driver based on UID and input data.
     *
     * @param  string  $driverUid  The UID of the driver to update
     * @param  array  $inputData  The input data to update the driver
     * @return array The formatted response after update
     */
    public function updateDriver(string $driverUid, array $inputData): array
    {
        return $this->entityCrudService->update(
            $this->driverModel,          // The model instance (Driver)
            $driverUid,                  // The driver UID
            $inputData,                  // The data to update
            'driver',                    // Cache key prefix for driver
            $this->transformer           // The transformer for Driver
        );
    }

    /**
     * Delete a driver based on UID.
     *
     * @param  string  $driverUid  The UID of the driver to delete
     * @return array The response after deletion
     */
    public function deleteDriver(string $driverUid): array
    {
        return $this->entityCrudService->delete(
            $this->driverModel,          // The model instance (Driver)
            $driverUid,                  // The driver UID
            'driver'                     // Cache key prefix for driver
        );
    }
}
