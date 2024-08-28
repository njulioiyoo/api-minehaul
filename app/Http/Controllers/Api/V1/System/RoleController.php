<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\System\Role\StoreRoleRequest;
use App\Http\Requests\System\Role\UpdateRoleRequest;
use App\Services\RequestHelperService;
use App\Services\System\Role\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LaravelJsonApi\Core\Responses\ErrorResponse;
use LaravelJsonApi\Core\Document\Error;
use LaravelJsonApi\Core\Responses\DataResponse;

class RoleController extends Controller
{
    protected $roleService;

    protected $requestHelperService;

    public function __construct(RoleService $roleService, RequestHelperService $requestHelperService)
    {
        $this->roleService = $roleService;
        $this->requestHelperService = $requestHelperService;
    }

    public function createRole(StoreRoleRequest $request)
    {
        $validatedData = $request->validated();
        $role = $this->roleService->createRole($validatedData);

        return new DataResponse($role);
    }

    public function readRole(Request $request)
    {
        $queryParams = $request->query();

        try {
            $response = $this->roleService->readRole($queryParams);
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error("Error reading role: {$e->getMessage()}");
            return new ErrorResponse(collect([
                Error::fromArray([
                    'status' => '500',
                    'title' => 'Internal Server Error',
                    'detail' => 'An error occurred while reading the role.'
                ])
            ]));
        }
    }

    public function updateRole(UpdateRoleRequest $request)
    {
        $validatedData = $request->validated();
        [$input, $roleId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'roles', true);
        $role = $this->roleService->updateRole($roleId, $validatedData);

        return new DataResponse($role);
    }

    public function deleteRole(Request $request)
    {
        [$input, $roleId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'roles', true);

        try {
            $this->roleService->deleteRole($roleId);
            return response()->json(['message' => 'Role deleted successfully.']);
        } catch (\Exception $e) {
            Log::error("Error deleting role: {$e->getMessage()}");
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
