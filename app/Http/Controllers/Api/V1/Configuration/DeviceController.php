<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\Device\StoreDeviceRequest;
use App\Http\Requests\Configuration\Device\UpdateDeviceRequest;
use App\Services\Configuration\Device\DeviceService;
use App\Services\RequestHelperService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LaravelJsonApi\Core\Document\Error;
use LaravelJsonApi\Core\Responses\ErrorResponse;
use LaravelJsonApi\Core\Responses\DataResponse;

class DeviceController extends Controller
{
    protected $deviceService;
    protected $requestHelperService;

    public function __construct(DeviceService $deviceService, RequestHelperService $requestHelperService)
    {
        $this->deviceService = $deviceService;
        $this->requestHelperService = $requestHelperService;
    }

    public function createDevice(StoreDeviceRequest $request)
    {
        $validatedData = $request->all();
        $device = $this->deviceService->createDevice($validatedData);

        return new DataResponse($device);
    }

    public function readDevice(Request $request)
    {
        $queryParams = $request->query();

        try {
            $response = $this->deviceService->readDevice($queryParams);
            return new DataResponse((object)$response);
            // return response()->json($response);
        } catch (\Exception $e) {
            Log::error("Error reading devices: {$e->getMessage()}");
            return new ErrorResponse(collect([
                Error::fromArray([
                    'status' => '500',
                    'title' => 'Internal Server Error',
                    'detail' => 'An error occurred while reading the devices.'
                ])
            ]));
        }
    }

    public function updateDevice(UpdateDeviceRequest $request)
    {
        $validatedData = $request->all();
        [$input, $deviceUid, $queryParams] = $this->requestHelperService->getInputAndId($request, 'devices', true);
        $device = $this->deviceService->updateDevice($deviceUid, $validatedData);

        return new DataResponse($device);
    }

    public function deleteDevice(Request $request)
    {
        [$input, $deviceUid, $queryParams] = $this->requestHelperService->getInputAndId($request, 'devices', true);

        try {
            $this->deviceService->deleteDevice($deviceUid);
            return response()->json(['message' => 'Device deleted successfully.']);
        } catch (\Exception $e) {
            Log::error("Error deleting device: {$e->getMessage()}");
            return new ErrorResponse(collect([
                Error::fromArray([
                    'status' => '500',
                    'title' => 'Internal Server Error',
                    'detail' => $e->getMessage()
                ])
            ]));
        }
    }
}
