<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Controller;
use App\Services\HeaderService;
use App\Services\RequestHelperService;
use App\Services\System\Permission\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $headerService;

    protected $permissionService;

    protected $requestHelperService;

    public function __construct(HeaderService $headerService, PermissionService $permissionService, RequestHelperService $requestHelperService)
    {
        $this->headerService = $headerService;
        $this->permissionService = $permissionService;
        $this->requestHelperService = $requestHelperService;
    }

    public function createPermission(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);
        [$input, $permissionId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'permissions');

        return $this->permissionService->createPermission($input, $headers, $queryParams);
    }

    public function readPermission(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);
        $queryParams = $request->query();

        return $this->permissionService->readPermission($queryParams, $headers);
    }

    public function updatePermission(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);
        [$input, $permissionId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'permissions', true);

        // Panggil metode updateDevice dengan ID yang diperoleh
        return $this->permissionService->updatePermission($permissionId, $input, $headers, $queryParams);
    }

    public function deletePermission(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);
        [$input, $permissionId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'permissions', true);

        // Panggil metode deleteDevice dengan ID yang diperoleh
        return $this->permissionService->deletePermission($permissionId, $input, $headers, $queryParams);
    }
}
