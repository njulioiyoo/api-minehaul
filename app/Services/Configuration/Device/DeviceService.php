<?php

declare(strict_types=1);

namespace App\Services\Configuration\Device;

use App\Services\HttpService;

class DeviceService
{
    protected $httpService;

    public function __construct(HttpService $httpService)
    {
        $this->httpService = $httpService;
    }

    public function createDevice($inputData, $headers, $queryParams)
    {
        $data = [
            'headers' => $headers,
            'json' => $inputData,
            'query' => $queryParams,
        ];

        return $this->httpService->handleRequest('post', route('v1.devices.store'), $data);
    }

    public function readDevice($queryParams, $headers)
    {
        $data = [
            'headers' => $headers,
            'query' => $queryParams,
        ];

        return $this->httpService->handleRequest('get', route('v1.devices.index'), $data);
    }

    public function updateDevice($deviceUid, $inputData, $headers, $queryParams)
    {
        $data = [
            'headers' => $headers,
            'json' => $inputData,
            'query' => $queryParams,
        ];

        return $this->httpService->handleRequest('patch', route('v1.devices.update', ['device' => $deviceUid]), $data);
    }

    public function deleteDevice($deviceUid, $inputData, $headers, $queryParams)
    {
        $data = [
            'headers' => $headers,
            'json' => $inputData,
            'query' => $queryParams,
        ];

        return $this->httpService->handleRequest('delete', route('v1.devices.destroy', ['device' => $deviceUid]), $data);
    }
}
