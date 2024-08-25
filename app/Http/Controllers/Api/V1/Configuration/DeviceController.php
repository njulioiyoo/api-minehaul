<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Configuration;

use App\Http\Controllers\Controller;
use App\Services\Configuration\Device\DeviceService;
use App\Services\HeaderService;
use App\Services\RequestHelperService;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    protected $deviceService;

    protected $headerService;

    protected $requestHelperService;

    public function __construct(DeviceService $deviceService, HeaderService $headerService, RequestHelperService $requestHelperService)
    {
        $this->deviceService = $deviceService;
        $this->headerService = $headerService;
        $this->requestHelperService = $requestHelperService;
    }

    public function createDevice(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);
        [$input, $deviceId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'devices');

        return $this->deviceService->createDevice($input, $headers, $queryParams);
    }

    public function readDevice(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);
        $queryParams = $request->query();

        return $this->deviceService->readDevice($queryParams, $headers);
    }

    public function updateDevice(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);
        [$input, $deviceUid, $queryParams] = $this->requestHelperService->getInputAndId($request, 'devices', true);

        // Panggil metode updateDevice dengan ID yang diperoleh
        return $this->deviceService->updateDevice($deviceUid, $input, $headers, $queryParams);
    }

    public function deleteDevice(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);
        [$input, $deviceUid, $queryParams] = $this->requestHelperService->getInputAndId($request, 'devices', true);

        // Panggil metode deleteDevice dengan ID yang diperoleh
        return $this->deviceService->deleteDevice($deviceUid, $input, $headers, $queryParams);
    }
}
