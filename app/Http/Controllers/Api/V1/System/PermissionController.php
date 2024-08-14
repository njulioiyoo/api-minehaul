<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Controller;
use App\Services\HeaderService;
use App\Services\System\Permission\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $headerService;

    protected $permissionService;

    public function __construct(HeaderService $headerService, PermissionService $permissionService)
    {
        $this->headerService = $headerService;
        $this->permissionService = $permissionService;
    }

    public function createPermission(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);

        $input = $request->json()->all();
        $input['data']['type'] = 'permissions';

        $queryParams = $request->query();

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

        $input = $request->json()->all();
        $permissionId = isset($input['data']['id']) ? $input['data']['id'] : null;
        $input['data']['type'] = 'permissions';

        $queryParams = $request->query();

        // Panggil metode updateDevice dengan ID yang diperoleh
        return $this->permissionService->updatePermission($permissionId, $input, $headers, $queryParams);
    }

    public function deletePermission(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);

        $input = $request->json()->all();
        $permissionId = isset($input['data']['id']) ? $input['data']['id'] : null;
        $input['data']['type'] = 'permissions';

        $queryParams = $request->query();

        // Panggil metode deleteDevice dengan ID yang diperoleh
        return $this->permissionService->deletePermission($permissionId, $input, $headers, $queryParams);
    }
}
