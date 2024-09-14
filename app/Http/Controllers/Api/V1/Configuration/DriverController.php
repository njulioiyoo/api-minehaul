<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\Driver\StoreDriverRequest;
use App\Http\Requests\Configuration\Driver\UpdateDriverRequest;
use App\Services\Configuration\Driver\DriverService;
use App\Services\RequestHelperService;
use App\Traits\ExceptionHandlerTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    use ExceptionHandlerTrait;

    protected DriverService $driverService;

    protected RequestHelperService $requestHelperService;

    /**
     * Constructor to initialize services.
     */
    public function __construct(DriverService $driverService, RequestHelperService $requestHelperService)
    {
        $this->driverService = $driverService;
        $this->requestHelperService = $requestHelperService;
    }

    /**
     * Create a new driver.
     *
     * @param  StoreDriverRequest  $request  The incoming request containing driver data.
     * @return JsonResponse The created driver with a 201 status code.
     */
    public function createDriver(StoreDriverRequest $request): JsonResponse
    {
        try {
            // Validate and retrieve request data
            $validatedData = $request->validated();
            // Create the driver using the service
            $driver = $this->driverService->createDriver($validatedData);

            // Return the created driver with a 201 Created status
            return response()->json($driver, 201);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return $this->handleException($e, 'Error creating driver');
        }
    }

    /**
     * Read drivers based on query parameters.
     *
     * @param  Request  $request  The incoming request with query parameters.
     * @return JsonResponse A JSON response containing the list of drivers.
     */
    public function readDriver(Request $request): JsonResponse
    {
        try {
            // Get query parameters from the request
            $queryParams = $request->query();
            // Retrieve drivers using the service
            $response = $this->driverService->readDriver($queryParams);

            // Return the drivers
            return response()->json($response);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return $this->handleException($e, 'Error reading drivers');
        }
    }

    /**
     * Show details of a specific driver.
     *
     * @param  Request  $request  The incoming request containing the driver ID.
     * @return JsonResponse The details of the requested driver.
     */
    public function showDriver(Request $request): JsonResponse
    {
        try {
            // Retrieve input and driver ID from the request
            [, $driverUid] = $this->requestHelperService->getInputAndId($request, 'drivers', true);
            // Get driver details using the service
            $response = $this->driverService->showDriver($driverUid);

            // Return the driver details
            return response()->json($response);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return $this->handleException($e, 'Error showing driver');
        }
    }

    /**
     * Update a specific driver.
     *
     * @param  UpdateDriverRequest  $request  The incoming request containing updated driver data.
     * @return JsonResponse The updated driver details.
     */
    public function updateDriver(UpdateDriverRequest $request): JsonResponse
    {
        try {
            // Validate and retrieve request data
            $validatedData = $request->validated();
            // Retrieve input and driver ID from the request
            [, $driverUid] = $this->requestHelperService->getInputAndId($request, 'drivers', true);
            // Update the driver using the service
            $driver = $this->driverService->updateDriver($driverUid, $validatedData);

            // Return the updated driver
            return response()->json($driver);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return $this->handleException($e, 'Error updating driver');
        }
    }

    /**
     * Delete a specific driver.
     *
     * @param  Request  $request  The incoming request containing the driver ID.
     * @return JsonResponse A 204 status with no content after successful deletion.
     */
    public function deleteDriver(Request $request): JsonResponse
    {
        try {
            // Retrieve input and driver ID from the request
            [, $driverUid] = $this->requestHelperService->getInputAndId($request, 'drivers', true);
            // Delete the driver using the service
            $this->driverService->deleteDriver($driverUid);

            // Return a 204 No Content status
            return response()->json(null, 204);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return $this->handleException($e, 'Error deleting driver');
        }
    }
}
