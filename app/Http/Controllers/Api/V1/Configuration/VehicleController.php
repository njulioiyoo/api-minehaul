<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\Vehicle\StoreVehicleRequest;
use App\Http\Requests\Configuration\Vehicle\UpdateVehicleRequest;
use App\Services\Configuration\Vehicle\VehicleService;
use App\Services\RequestHelperService;
use App\Traits\ExceptionHandlerTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    use ExceptionHandlerTrait;

    protected VehicleService $vehicleService;

    protected RequestHelperService $requestHelperService;

    /**
     * Constructor to initialize services.
     */
    public function __construct(VehicleService $vehicleService, RequestHelperService $requestHelperService)
    {
        $this->vehicleService = $vehicleService;
        $this->requestHelperService = $requestHelperService;
    }

    /**
     * Create a new vehicle.
     */
    public function createVehicle(StoreVehicleRequest $request): JsonResponse
    {
        try {
            // Validate and retrieve request data
            $validatedData = $request->validated();
            // Create the vehicle using the service
            $vehicle = $this->vehicleService->createVehicle($validatedData);

            // Return the created vehicle with a 201 Created status
            return response()->json($vehicle, 201);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return $this->handleException($e, 'Error creating vehicle');
        }
    }

    /**
     * Read vehicles based on query parameters.
     */
    public function readVehicle(Request $request): JsonResponse
    {
        try {
            // Get query parameters from the request
            $queryParams = $request->query();
            // Retrieve vehicles using the service
            $response = $this->vehicleService->readVehicle($queryParams);

            // Return the vehicles
            return response()->json($response);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return $this->handleException($e, 'Error reading vehicles');
        }
    }

    /**
     * Show details of a specific vehicle.
     */
    public function showVehicle(Request $request): JsonResponse
    {
        try {
            // Retrieve input and vehicle ID from the request
            [, $vehicleUid] = $this->requestHelperService->getInputAndId($request, 'vehicles', true);
            // Get vehicle details using the service
            $response = $this->vehicleService->showVehicle($vehicleUid);

            // Return the vehicle details
            return response()->json($response);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return $this->handleException($e, 'Error showing vehicle');
        }
    }

    /**
     * Update a specific vehicle.
     */
    public function updateVehicle(UpdateVehicleRequest $request): JsonResponse
    {
        try {
            // Validate and retrieve request data
            $validatedData = $request->validated();
            // Retrieve input and vehicle ID from the request
            [, $vehicleUid] = $this->requestHelperService->getInputAndId($request, 'vehicles', true);
            // Update the vehicle using the service
            $vehicle = $this->vehicleService->updateVehicle($vehicleUid, $validatedData);

            // Return the updated vehicle
            return response()->json($vehicle);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return $this->handleException($e, 'Error updating vehicle');
        }
    }

    /**
     * Delete a specific vehicle.
     */
    public function deleteVehicle(Request $request): JsonResponse
    {
        try {
            // Retrieve input and vehicle ID from the request
            [, $vehicleUid] = $this->requestHelperService->getInputAndId($request, 'vehicles', true);
            // Delete the vehicle using the service
            $this->vehicleService->deleteVehicle($vehicleUid);

            // Return a 204 No Content status
            return response()->json(null, 204);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return $this->handleException($e, 'Error deleting vehicle');
        }
    }
}
