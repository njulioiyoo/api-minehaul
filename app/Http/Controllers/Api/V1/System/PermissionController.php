<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\System\Permission\StorePermissionRequest;
use App\Http\Requests\System\Permission\UpdatePermissionRequest;
use App\Services\RequestHelperService;
use App\Services\System\Permission\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LaravelJsonApi\Core\Responses\ErrorResponse;
use LaravelJsonApi\Core\Document\Error;
use LaravelJsonApi\Core\Responses\DataResponse;


class PermissionController extends Controller
{
    protected $permissionService;

    protected $requestHelperService;

    public function __construct(PermissionService $permissionService, RequestHelperService $requestHelperService)
    {
        $this->permissionService = $permissionService;
        $this->requestHelperService = $requestHelperService;
    }

    public function createPermission(StorePermissionRequest $request)
    {
        $validatedData = $request->validated();
        $device = $this->permissionService->createPermission($validatedData);

        return new DataResponse($device);
    }

    public function readPermission(Request $request)
    {
        $queryParams = $request->query();

        try {
            $response = $this->permissionService->readPermission($queryParams);
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

    public function updatePermission(UpdatePermissionRequest $request)
    {
        $validatedData = $request->validated();
        [$input, $permissionId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'permissions', true);
        $role = $this->permissionService->updatePermission($permissionId, $validatedData);

        return new DataResponse($role);
    }

    public function deletePermission(Request $request)
    {
        [$input, $permissionId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'permissions', true);

        try {
            $this->permissionService->deletePermission($permissionId);
            return response()->json(['message' => 'Permission deleted successfully.']);
        } catch (\Exception $e) {
            Log::error("Error deleting permission: {$e->getMessage()}");
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
