<?php

declare(strict_types=1);

namespace App\Services\Configuration\Location;

use App\Models\Location;
use App\Services\Configuration\EntityCrudService;
use App\Traits\ExceptionHandlerTrait;
use App\Transformers\LocationTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;

class LocationService
{
    use ExceptionHandlerTrait;

    protected EntityCrudService $entityCrudService;

    protected LocationTransformer $transformer;

    protected Location $locationModel;

    /**
     * Constructor for dependency injection
     */
    public function __construct(EntityCrudService $entityCrudService, LocationTransformer $transformer, Location $location)
    {
        $this->entityCrudService = $entityCrudService;
        $this->transformer = $transformer;
        $this->locationModel = $location;
    }

    /**
     * Create a new location in the database and clear related cache.
     *
     * @param  array  $inputData  Input data for creating the location
     * @return mixed Formatted JSON API response
     */
    public function createLocation(array $inputData)
    {
        // Call the generic create method from EntityCrudService
        return $this->entityCrudService->create(
            $this->locationModel,            // Model
            $inputData,                    // Input data
            'location',                       // Cache key prefix
            $this->transformer              // Transformer
        );
    }

    /**
     * Read a list of locations based on query parameters.
     *
     * @param  array  $queryParams  Query parameters for filtering and pagination
     * @return array Paginated and formatted location data
     */
    public function readLocation(array $queryParams)
    {
        // Define the relationships for the Location model
        $relations = ['account', 'pit'];

        // Call the generic read method from EntityCrudService
        return $this->entityCrudService->read(
            $this->locationModel,            // The model instance (Location)
            $queryParams,                  // The query parameters
            $this->transformer,            // The transformer for Location
            $relations                     // Relationships to eager load
        );
    }

    /**
     * Show the details of a location by UID.
     *
     * @param  string  $locationUid  UID of the location to be displayed
     * @return mixed Formatted JSON API response
     *
     * @throws ModelNotFoundException If the location is not found
     */
    public function showLocation(string $locationUid)
    {
        return $this->entityCrudService->show(
            $this->locationModel,          // The model instance (Location)
            $locationUid,                  // The location UID
            'location',                    // Cache key prefix for location
            $this->transformer           // The transformer for Location
        );
    }

    /**
     * Update the data of a location by UID.
     *
     * @param  string  $locationUid  UID of the location to be updated
     * @param  array  $inputData  Input data for the update
     * @return mixed Formatted JSON API response
     *
     * @throws ModelNotFoundException If the location is not found
     */
    public function updateLocation(string $locationUid, array $inputData)
    {
        return $this->entityCrudService->update(
            $this->locationModel,          // The model instance (Location)
            $locationUid,                  // The location UID
            $inputData,                  // The data to update
            'location',                    // Cache key prefix for location
            $this->transformer           // The transformer for Location
        );
    }

    /**
     * Delete a location by UID.
     *
     * @param  string  $locationUid  UID of the location to be deleted
     * @return mixed JSON response confirming the deletion
     *
     * @throws \Throwable If an error occurs during deletion
     */
    public function deleteLocation(string $locationUid)
    {
        return $this->entityCrudService->delete(
            $this->locationModel,          // The model instance (Location)
            $locationUid,                  // The location UID
            'location'                     // Cache key prefix for location
        );
    }
}
