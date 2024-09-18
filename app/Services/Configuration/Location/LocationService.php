<?php

declare(strict_types=1);

namespace App\Services\Configuration\Location;

use App\Helpers\PaginationHelper;
use App\Models\Location;
use App\Traits\ExceptionHandlerTrait;
use App\Transformers\LocationTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LocationService
{
    use ExceptionHandlerTrait;

    protected LocationTransformer $transformer;

    protected Location $locationModel;

    /**
     * Constructor for dependency injection
     */
    public function __construct(LocationTransformer $transformer, Location $location)
    {
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
        return DB::transaction(function () use ($inputData) {
            $location = $this->locationModel->create($inputData);

            // Forget cache for the newly created location
            Cache::forget("location_{$location->uid}");

            // Return formatted JSON API response
            return $this->formatJsonApiResponse(
                $this->transformer->transform($location)
            );
        });
    }

    /**
     * Read a list of locations based on query parameters.
     *
     * @param  array  $queryParams  Query parameters for filtering and pagination
     * @return array Paginated and formatted location data
     */
    public function readLocation(array $queryParams)
    {
        $perPage = $queryParams['page']['size'] ?? 10;
        $page = $queryParams['page']['number'] ?? 1;

        $query = $this->locationModel->with(['account', 'pit']);

        // Apply filters if any
        if (isset($queryParams['filter'])) {
            foreach ($queryParams['filter'] as $field => $value) {
                $query->where($field, $value);
            }
        }

        // Get paginated location data
        $locations = $query->paginate($perPage, ['*'], 'page[number]', $page);

        // Transform location data using the transformer
        $data = $locations->map(fn ($location) => $this->transformer->transform($location))->values()->all();

        // Return paginated data with formatting
        return PaginationHelper::format($locations, $data);
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
        $location = Cache::remember("location_$locationUid", 60, function () use ($locationUid) {
            return $this->locationModel->where('uid', $locationUid)->first();
        });

        if (! $location) {
            throw new ModelNotFoundException('Location not found');
        }

        return $this->formatJsonApiResponse(
            $this->transformer->transform($location)
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
        return DB::transaction(function () use ($locationUid, $inputData) {
            $this->locationModel->where('uid', $locationUid)->update($inputData);

            $location = $this->locationModel->where('uid', $locationUid)->first();

            if ($location) {
                Cache::put("location_$locationUid", $location, 60);

                return $this->formatJsonApiResponse(
                    $this->transformer->transform($location)
                );
            }

            throw new ModelNotFoundException('Location not found');
        });
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
        try {
            $location = $this->locationModel->where('uid', $locationUid)->first();

            if (! $location) {
                throw new ModelNotFoundException('Location not found');
            }

            $location->delete();

            Cache::forget("location_$locationUid");

            return response()->json(['message' => 'Location deleted successfully']);
        } catch (\Throwable $th) {
            Log::error("Error deleting location with UID: {$locationUid}, Error: {$th->getMessage()}");
            throw $th;
        }
    }

    /**
     * Helper function to format JSON API responses.
     *
     * @param  array  $data  Transformed data to be returned in the response
     * @return mixed Formatted JSON API response
     */
    private function formatJsonApiResponse(array $data)
    {
        return response()->json(['data' => $data], 200);
    }
}
