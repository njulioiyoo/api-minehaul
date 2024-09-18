<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\Location\StoreLocationRequest;
use App\Http\Requests\Configuration\Location\UpdateLocationRequest;
use App\Services\Configuration\Location\LocationService;
use App\Services\RequestHelperService;
use App\Traits\ExceptionHandlerTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    use ExceptionHandlerTrait;

    protected LocationService $locationService;

    protected RequestHelperService $requestHelperService;

    /**
     * Constructor to initialize services.
     */
    public function __construct(LocationService $locationService, RequestHelperService $requestHelperService)
    {
        $this->locationService = $locationService;
        $this->requestHelperService = $requestHelperService;
    }

    /**
     * Create a new location.
     *
     * @param  StoreLocationRequest  $request  The incoming request containing location data.
     * @return \Illuminate\Http\JsonResponse The created location with a 201 status code.
     */
    public function createLocation(StoreLocationRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $location = $this->locationService->createLocation($validatedData);

            return response()->json($location, 201);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->handleException($e, 'Error creating location');
        }
    }

    /**
     * Read locations based on query parameters.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming request with query parameters.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the list of locations.
     */
    public function readLocation(Request $request): JsonResponse
    {
        try {
            $queryParams = $request->query();
            $response = $this->locationService->readLocation($queryParams);

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->handleException($e, 'Error reading locations');
        }
    }

    /**
     * Show details of a specific location.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming request containing the location ID.
     * @return \Illuminate\Http\JsonResponse The details of the requested location.
     */
    public function showLocation(Request $request): JsonResponse
    {
        try {
            [, $locationUid] = $this->requestHelperService->getInputAndId($request, 'locations', true);
            $response = $this->locationService->showLocation($locationUid);

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->handleException($e, 'Error showing location');
        }
    }

    /**
     * Update a specific location.
     *
     * @param  UpdateLocationRequest  $request  The incoming request containing updated location data.
     * @return \Illuminate\Http\JsonResponse The updated location details.
     */
    public function updateLocation(UpdateLocationRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            [, $locationUid] = $this->requestHelperService->getInputAndId($request, 'locations', true);
            $location = $this->locationService->updateLocation($locationUid, $validatedData);

            return response()->json($location);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->handleException($e, 'Error updating location');
        }
    }

    /**
     * Delete a specific location.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming request containing the location ID.
     * @return \Illuminate\Http\JsonResponse A 204 status with no content after successful deletion.
     */
    public function deleteLocation(Request $request): JsonResponse
    {
        try {
            [, $locationUid] = $this->requestHelperService->getInputAndId($request, 'locations', true);
            $this->locationService->deleteLocation($locationUid);

            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->handleException($e, 'Error deleting location');
        }
    }
}
