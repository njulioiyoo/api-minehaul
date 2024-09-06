<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\Device\StoreDeviceRequest;
use App\Http\Requests\Configuration\Device\UpdateDeviceRequest;
use App\Services\Configuration\Device\DeviceService;
use App\Services\RequestHelperService;
use Illuminate\Http\Request;
use App\Traits\ExceptionHandlerTrait;

class DeviceController extends Controller
{
    use ExceptionHandlerTrait;

    protected $deviceService;
    protected $requestHelperService;

    public function __construct(DeviceService $deviceService, RequestHelperService $requestHelperService)
    {
        $this->deviceService = $deviceService;
        $this->requestHelperService = $requestHelperService;
    }

    public function createDevice(StoreDeviceRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $device = $this->deviceService->createDevice($validatedData);

            return response()->json($device);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error creating devices');
        }
    }

    public function readDevice(Request $request)
    {
        try {
            $queryParams = $request->query();
            $response = $this->deviceService->readDevice($queryParams);

            return response()->json($response);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error reading devices');
        }
    }

    public function updateDevice(UpdateDeviceRequest $request)
    {
        try {
            $validatedData = $request->validated();
            [$input, $deviceUid, $queryParams] = $this->requestHelperService->getInputAndId($request, 'devices', true);

            $device = $this->deviceService->updateDevice($deviceUid, $validatedData);

            return response()->json($device);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error updating devices');
        }
    }

    public function deleteDevice(Request $request)
    {
        try {
            [$input, $deviceUid, $queryParams] = $this->requestHelperService->getInputAndId($request, 'devices', true);
            $this->deviceService->deleteDevice($deviceUid);

            // Jika tidak ada data lain yang perlu dikembalikan maka kembalikan status 204 No Content
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error deleting devices');
        }
    }
}
