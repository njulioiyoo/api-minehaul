<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\System\Role\StoreRoleRequest;
use App\Http\Requests\System\Role\UpdateRoleRequest;
use App\Services\RequestHelperService;
use App\Services\System\Role\RoleService;
use App\Traits\ExceptionHandlerTrait;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use ExceptionHandlerTrait;

    protected $roleService;

    protected $requestHelperService;

    public function __construct(RoleService $roleService, RequestHelperService $requestHelperService)
    {
        $this->roleService = $roleService;
        $this->requestHelperService = $requestHelperService;
    }

    public function createRole(StoreRoleRequest $request)
    {
        try {
            $validatedData = $request->all();
            $permissions = $validatedData['permissions'] ?? [];
            $role = $this->roleService->createRole($validatedData, $permissions);

            return response()->json($role);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error creating role');
        }
    }

    public function readRole(Request $request)
    {
        try {
            $queryParams = $request->query();
            $response = $this->roleService->readRole($queryParams);

            return response()->json($response);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error reading role');
        }
    }

    public function updateRole(UpdateRoleRequest $request)
    {
        try {
            $validatedData = $request->all();
            [$input, $roleId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'roles', true);
            $permissions = $validatedData['permissions'] ?? [];

            $role = $this->roleService->updateRole($roleId, $validatedData, $permissions);

            return response()->json($role);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error updating role');
        }
    }

    public function deleteRole(Request $request)
    {
        try {
            [$input, $roleId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'roles', true);
            $this->roleService->deleteRole($roleId);

            return response()->json(['message' => 'Role deleted successfully.']);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error deleting role');
        }
    }
}
