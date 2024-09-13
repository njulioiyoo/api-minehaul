<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\Device\StoreDeviceRequest;
use App\Http\Requests\Configuration\Device\UpdateDeviceRequest;
use App\Services\Configuration\Device\DeviceService;
use App\Services\RequestHelperService;
use App\Traits\ExceptionHandlerTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    use ExceptionHandlerTrait;

    protected DeviceService $deviceService;

    protected RequestHelperService $requestHelperService;

    /**
     * Constructor to initialize services.
     */
    public function __construct(DeviceService $deviceService, RequestHelperService $requestHelperService)
    {
        $this->deviceService = $deviceService;
        $this->requestHelperService = $requestHelperService;
    }

    /**
     * Create a new device.
     */
    public function createDevice(StoreDeviceRequest $request): JsonResponse
    {
        try {
            // Validate and retrieve request data
            $validatedData = $request->validated();
            // Create the device using the service
            $device = $this->deviceService->createDevice($validatedData);

            // Return the created device with a 201 Created status
            return response()->json($device, 201);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return $this->handleException($e, 'Error creating device');
        }
    }

    /**
     * Read devices based on query parameters.
     */
    public function readDevice(Request $request): JsonResponse
    {
        try {
            // Get query parameters from the request
            $queryParams = $request->query();
            // Retrieve devices using the service
            $response = $this->deviceService->readDevice($queryParams);

            // Return the devices
            return response()->json($response);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return $this->handleException($e, 'Error reading devices');
        }
    }

    /**
     * Show details of a specific device.
     */
    public function showDevice(Request $request): JsonResponse
    {
        try {
            // Retrieve input and device ID from the request
            [, $deviceUid] = $this->requestHelperService->getInputAndId($request, 'devices', true);
            // Get device details using the service
            $response = $this->deviceService->showDevice($deviceUid);

            // Return the device details
            return response()->json($response);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return $this->handleException($e, 'Error showing device');
        }
    }

    /**
     * Update a specific device.
     */
    public function updateDevice(UpdateDeviceRequest $request): JsonResponse
    {
        try {
            // Validate and retrieve request data
            $validatedData = $request->validated();
            // Retrieve input and device ID from the request
            [, $deviceUid] = $this->requestHelperService->getInputAndId($request, 'devices', true);
            // Update the device using the service
            $device = $this->deviceService->updateDevice($deviceUid, $validatedData);

            // Return the updated device
            return response()->json($device);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return $this->handleException($e, 'Error updating device');
        }
    }

    /**
     * Delete a specific device.
     */
    public function deleteDevice(Request $request): JsonResponse
    {
        try {
            // Retrieve input and device ID from the request
            [, $deviceUid] = $this->requestHelperService->getInputAndId($request, 'devices', true);
            // Delete the device using the service
            $this->deviceService->deleteDevice($deviceUid);

            // Return a 204 No Content status
            return response()->json(null, 204);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return $this->handleException($e, 'Error deleting device');
        }
    }
}
