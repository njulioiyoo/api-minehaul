<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Configuration;

use App\Http\Controllers\Controller;
use App\Services\Configuration\Device\DeviceService;
use App\Services\HeaderService;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    protected $deviceService;

    protected $headerService;

    public function __construct(DeviceService $deviceService, HeaderService $headerService)
    {
        $this->deviceService = $deviceService;
        $this->headerService = $headerService;
    }

    public function createDevice(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);

        $input = $request->json()->all();
        $input['data']['type'] = 'devices';

        $queryParams = $request->query();

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

        $input = $request->json()->all();
        $deviceId = isset($input['data']['id']) ? $input['data']['id'] : null;
        $input['data']['type'] = 'devices';

        $queryParams = $request->query();

        // Panggil metode updateDevice dengan ID yang diperoleh
        return $this->deviceService->updateDevice($deviceId, $input, $headers, $queryParams);
    }

    public function deleteDevice(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);

        $input = $request->json()->all();
        $deviceId = isset($input['data']['id']) ? $input['data']['id'] : null;
        $input['data']['type'] = 'devices';

        $queryParams = $request->query();

        // Panggil metode deleteDevice dengan ID yang diperoleh
        return $this->deviceService->deleteDevice($deviceId, $input, $headers, $queryParams);
    }
}
