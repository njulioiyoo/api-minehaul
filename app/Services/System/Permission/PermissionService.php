<?php

declare(strict_types=1);

namespace App\Services\System\Permission;

use App\Helpers\PaginationHelper;
use App\Transformers\PermissionTransformer;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;

class PermissionService
{
    protected $transformer;

    public function __construct(PermissionTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function createPermission(array $inputData)
    {
        $permission = Permission::create($inputData);

        if (!$permission) {
            throw new \Exception('Failed to create permission');
        }

        return $permission;
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
        $permission = Permission::find($permissionId);

        if (!$permission) {
            throw new \Exception('Permission not found');
        }

        $permission->update($inputData);

        return $permission;
    }

    public function deletePermission($permissionId)
    {
        $permission = Permission::find($permissionId);

        if (!$permission) {
            Log::info('Permission not found with ID: ' . $permissionId);
            throw new \Exception('Role not found');
        }

        $permission->delete();
    }
}
