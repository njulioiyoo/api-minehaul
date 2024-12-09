<?php

declare(strict_types=1);

namespace App\Services\Configuration\Device;

use App\Models\Device;
use App\Services\Configuration\EntityCrudService;
use App\Traits\ExceptionHandlerTrait;
use App\Transformers\DeviceTransformer;

class DeviceService
{
    use ExceptionHandlerTrait;

    protected EntityCrudService $entityCrudService;

    protected DeviceTransformer $transformer;

    protected Device $deviceModel;

    // Constructor for dependency injection
    public function __construct(EntityCrudService $entityCrudService, DeviceTransformer $transformer, Device $device)
    {
        $this->entityCrudService = $entityCrudService;
        $this->transformer = $transformer;
        $this->deviceModel = $device;
    }

    /**
     * Create a new device in the database and clear related cache.
     *
     * @param  array  $inputData  Input data for creating the device
     * @return mixed Formatted JSON API response
     */
    public function createDevice(array $inputData)
    {
        // Call the generic create method from EntityCrudService
        return $this->entityCrudService->create(
            $this->deviceModel,            // Model
            $inputData,                    // Input data
            'device',                       // Cache key prefix
            $this->transformer              // Transformer
        );
    }

    /**
     * Read devices with pagination and filters.
     *
     * @param  array  $queryParams  Query parameters including filters and pagination info.
     * @return array The paginated data with formatted response.
     */
    public function readDevice(array $queryParams): array
    {
        // Define the relationships for the Device model
        $relations = ['account', 'pit', 'deviceType', 'deviceMake', 'deviceModel'];

        // Call the generic read method from EntityCrudService
        return $this->entityCrudService->read(
            $this->deviceModel,            // The model instance (Device)
            $queryParams,                  // The query parameters
            $this->transformer,            // The transformer for Device
            $relations                     // Relationships to eager load
        );
    }

    /**
     * Show a single device based on UID.
     *
     * @param  string  $deviceUid  The UID of the device to retrieve
     * @return array The formatted response after retrieval
     */
    public function showDevice(string $deviceUid): array
    {
        return $this->entityCrudService->show(
            $this->deviceModel,          // The model instance (Device)
            $deviceUid,                  // The device UID
            'device',                    // Cache key prefix for device
            $this->transformer           // The transformer for Device
        );
    }

    /**
     * Update a device based on UID and input data.
     *
     * @param  string  $deviceUid  The UID of the device to update
     * @param  array  $inputData  The input data to update the device
     * @return array The formatted response after update
     */
    public function updateDevice(string $deviceUid, array $inputData): array
    {
        return $this->entityCrudService->update(
            $this->deviceModel,          // The model instance (Device)
            $deviceUid,                  // The device UID
            $inputData,                  // The data to update
            'device',                    // Cache key prefix for device
            $this->transformer           // The transformer for Device
        );
    }

    /**
     * Delete a device based on UID.
     *
     * @param  string  $deviceUid  The UID of the device to delete
     * @return array The response after deletion
     */
    public function deleteDevice(string $deviceUid): array
    {
        return $this->entityCrudService->delete(
            $this->deviceModel,          // The model instance (Device)
            $deviceUid,                  // The device UID
            'device'                     // Cache key prefix for device
        );
    }
}
