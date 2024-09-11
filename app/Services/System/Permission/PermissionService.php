<?php

declare(strict_types=1);

namespace App\Services\System\Permission;

use App\Helpers\PaginationHelper;
use App\Traits\ExceptionHandlerTrait;
use App\Transformers\PermissionTransformer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    use ExceptionHandlerTrait;

    protected $transformer;

    protected $permissionModel;

    public function __construct(PermissionTransformer $transformer, Permission $permission)
    {
        $this->transformer = $transformer;
        $this->permissionModel = $permission;
    }

    public function createPermission(array $inputData)
    {
        return DB::transaction(function () use ($inputData) {
            $permission = $this->permissionModel->create($inputData);

            // Clear cache related to permissions
            Cache::forget('permission_'.$permission->id);

            return $this->formatJsonApiResponse(
                $this->transformer->transform($permission)
            );
        });
    }

    public function readPermission(array $queryParams)
    {
        $perPage = $queryParams['page']['size'] ?? 10;
        $page = $queryParams['page']['number'] ?? 1;

        $query = Permission::query();

        if (isset($queryParams['filter'])) {
            foreach ($queryParams['filter'] as $field => $value) {
                $query->where($field, $value);
            }
        }

        $permissions = $query->paginate($perPage, ['*'], 'page[number]', $page);

        $data = $permissions->map(function ($permission) {
            return $this->transformer->transform($permission);
        })->values()->all(); // Convert to array

        return PaginationHelper::format($permissions, $data);
    }

    public function updatePermission(string $permissionId, array $inputData)
    {
        return DB::transaction(function () use ($permissionId, $inputData) {
            $permission = $this->permissionModel->findOrFail($permissionId);

            $permission->update($inputData);

            // Update cache
            Cache::put("permission_$permissionId", $permission, 60);

            // Menggunakan transformer untuk format response JSON API
            return $this->formatJsonApiResponse(
                $this->transformer->transform($permission)
            );
        });
    }

    public function deletePermission($permissionId)
    {
        try {
            $permission = $this->permissionModel->findOrFail($permissionId);
            $permission->delete();

            // Clear cache
            Cache::forget("permission_$permissionId");
        } catch (\Exception $e) {
            Log::error("Error deleting permission with ID: {$permissionId}, Error: {$e->getMessage()}");
            throw $e;
        }
    }
}
