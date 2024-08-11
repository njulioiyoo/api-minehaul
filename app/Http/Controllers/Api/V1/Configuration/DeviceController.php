<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Configuration;

use App\Http\Controllers\Controller;
use App\Services\Configuration\Device\DeviceService;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    protected $deviceService;

    public function __construct(DeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
    }

    public function createDevice(Request $request)
    {
        $headers = $this->prepareHeaders($request);

        $input = $request->json()->all();
        $input['data']['type'] = 'devices';

        $queryParams = $request->query();

        return $this->deviceService->createDevice($input, $headers, $queryParams);
    }

    public function readDevice(Request $request)
    {
        $headers = $this->prepareHeaders($request);
        $queryParams = $request->query();

        return $this->deviceService->readDevice($queryParams, $headers);
    }

    public function updateDevice(Request $request)
    {
        $headers = $this->prepareHeaders($request);

        $input = $request->json()->all();
        $deviceId = isset($input['data']['id']) ? $input['data']['id'] : null;
        $input['data']['type'] = 'devices';

        $queryParams = $request->query();

        // Panggil metode updateDevice dengan ID yang diperoleh
        return $this->deviceService->updateDevice($deviceId, $input, $headers, $queryParams);
    }

    public function deleteDevice(Request $request)
    {
        $headers = $this->prepareHeaders($request);

        $input = $request->json()->all();
        $deviceId = isset($input['data']['id']) ? $input['data']['id'] : null;
        $input['data']['type'] = 'devices';

        $queryParams = $request->query();

        // Panggil metode deleteDevice dengan ID yang diperoleh
        return $this->deviceService->deleteDevice($deviceId, $input, $headers, $queryParams);
    }

    private function prepareHeaders(Request $request)
    {
        return [
            'Accept' => 'application/vnd.api+json',
            'Authorization' => $request->header('Authorization'),
            'x-api-token' => $request->header('x-api-token'),
            'Content-Type' => 'application/vnd.api+json',
        ];
    }
}
