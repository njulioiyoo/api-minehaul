<?php

declare(strict_types=1);

namespace App\Services\Configuration\Permission;

use App\Services\HttpService;

class PermissionService
{
    protected $httpService;

    public function __construct(HttpService $httpService)
    {
        $this->httpService = $httpService;
    }

    public function createPermission($inputData, $headers, $queryParams)
    {
        $data = [
            'headers' => $headers,
            'json' => $inputData,
            'query' => $queryParams,
        ];

        return $this->httpService->handleRequest('post', route('v1.permissions.store'), $data);
    }

    public function readPermission($queryParams, $headers)
    {
        $data = [
            'headers' => $headers,
            'query' => $queryParams,
        ];

        return $this->httpService->handleRequest('get', route('v1.permissions.index'), $data);
    }

    public function updatePermission($permissionId, $inputData, $headers, $queryParams)
    {
        $data = [
            'headers' => $headers,
            'json' => $inputData,
            'query' => $queryParams,
        ];

        return $this->httpService->handleRequest('patch', route('v1.permissions.update', ['permission' => $permissionId]), $data);
    }

    public function deletePermission($permissionId, $inputData, $headers, $queryParams)
    {
        $data = [
            'headers' => $headers,
            'json' => $inputData,
            'query' => $queryParams,
        ];

        return $this->httpService->handleRequest('delete', route('v1.permissions.destroy', ['permission' => $permissionId]), $data);
    }
}
