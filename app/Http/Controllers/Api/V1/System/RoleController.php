<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Controller;
use App\Services\HeaderService;
use App\Services\System\Role\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $headerService;

    protected $roleService;

    public function __construct(HeaderService $headerService, RoleService $roleService)
    {
        $this->headerService = $headerService;
        $this->roleService = $roleService;
    }

    public function createRole(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);

        $input = $request->json()->all();
        $input['data']['type'] = 'roles';

        $queryParams = $request->query();

        return $this->roleService->createRole($input, $headers, $queryParams);
    }

    public function readRole(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);
        $queryParams = $request->query();

        return $this->roleService->readRole($queryParams, $headers);
    }

    public function updateRole(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);

        $input = $request->json()->all();
        $roleId = isset($input['data']['id']) ? $input['data']['id'] : null;
        $input['data']['type'] = 'roles';

        $queryParams = $request->query();

        // Panggil metode updateDevice dengan ID yang diperoleh
        return $this->roleService->updateRole($roleId, $input, $headers, $queryParams);
    }

    public function deleteRole(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);

        $input = $request->json()->all();
        $roleId = isset($input['data']['id']) ? $input['data']['id'] : null;
        $input['data']['type'] = 'roles';

        $queryParams = $request->query();

        // Panggil metode deleteDevice dengan ID yang diperoleh
        return $this->roleService->deleteRole($roleId, $input, $headers, $queryParams);
    }
}
