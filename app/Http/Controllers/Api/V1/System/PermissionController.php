<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\System\Permission\StorePermissionRequest;
use App\Http\Requests\System\Permission\UpdatePermissionRequest;
use App\Services\RequestHelperService;
use App\Services\System\Permission\PermissionService;
use Illuminate\Http\Request;
use LaravelJsonApi\Core\Responses\DataResponse;
use App\Traits\ExceptionHandlerTrait;

class PermissionController extends Controller
{
    use ExceptionHandlerTrait;

    protected $permissionService;
    protected $requestHelperService;

    public function __construct(PermissionService $permissionService, RequestHelperService $requestHelperService)
    {
        $this->permissionService = $permissionService;
        $this->requestHelperService = $requestHelperService;
    }

    public function createPermission(StorePermissionRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $permission = $this->permissionService->createPermission($validatedData);

            return new DataResponse($permission);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error creating permission');
        }
    }

    public function readPermission(Request $request)
    {
        $queryParams = $request->query();

        try {
            $response = $this->permissionService->readPermission($queryParams);
            return response()->json($response);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error reading permissions');
        }
    }

    public function updatePermission(UpdatePermissionRequest $request)
    {
        try {
            $validatedData = $request->validated();
            [$input, $permissionId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'permissions', true);
            $permission = $this->permissionService->updatePermission($permissionId, $validatedData);

            return new DataResponse($permission);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error updating permission');
        }
    }

    public function deletePermission(Request $request)
    {
        [$input, $permissionId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'permissions', true);

        try {
            $this->permissionService->deletePermission($permissionId);
            return response()->json(['message' => 'Permission deleted successfully.']);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error deleting permission');
        }
    }
}
