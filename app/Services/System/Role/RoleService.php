<?php

declare(strict_types=1);

namespace App\Services\System\Role;

use App\Helpers\PaginationHelper;
use App\Traits\ExceptionHandlerTrait;
use App\Transformers\RoleTransformer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RoleService
{
    use ExceptionHandlerTrait;

    protected $transformer;

    protected $roleModel;

    public function __construct(RoleTransformer $transformer, Role $role)
    {
        $this->transformer = $transformer;
        $this->roleModel = $role;
    }

    public function createRole(array $inputData, array $permissions = [])
    {
        return DB::transaction(function () use ($inputData, $permissions) {
            $role = $this->roleModel->create($inputData);

            // Clear cache related to roles
            Cache::forget('role_'.$role->id);

            if (! empty($permissions)) {
                $role->syncPermissions($permissions);
            }

            return $this->formatJsonApiResponse(
                $this->transformer->transform($role)
            );
        });
    }

    public function readRole(array $queryParams)
    {
        $perPage = $queryParams['page']['size'] ?? 10;
        $page = $queryParams['page']['number'] ?? 1;

        $query = Role::query();

        if (isset($queryParams['filter'])) {
            foreach ($queryParams['filter'] as $field => $value) {
                $query->where($field, $value);
            }
        }

        $roles = $query->paginate($perPage, ['*'], 'page[number]', $page);

        $data = $roles->map(function ($role) {
            return $this->transformer->transform($role);
        })->values()->all(); // Convert to array

        return PaginationHelper::format($roles, $data);
    }

    public function updateRole(string $roleId, array $inputData, array $permissions = [])
    {
        return DB::transaction(function () use ($roleId, $inputData, $permissions) {
            $role = $this->roleModel->findOrFail($roleId);

            $role->update($inputData);

            // Update cache
            Cache::put("role_$roleId", $role, 60);

            if (! empty($permissions)) {
                $role->syncPermissions($permissions);
            }

            // Menggunakan transformer untuk format response JSON API
            return $this->formatJsonApiResponse(
                $this->transformer->transform($role)
            );
        });
    }

    public function deleteRole($roleId)
    {
        try {
            $role = $this->roleModel->findOrFail($roleId);
            $role->delete();

            // Clear cache
            Cache::forget("role_$roleId");
        } catch (\Exception $e) {
            Log::error("Error deleting role with ID: {$roleId}, Error: {$e->getMessage()}");
            throw $e;
        }
    }

    public function showRole(string $roleId)
    {
        $role = Cache::remember("role_$roleId", 60, function () use ($roleId) {
            return $this->roleModel->where('id', $roleId)->firstOrFail();
        });

        // Menggunakan transformer untuk format response JSON API
        return $this->formatJsonApiResponse(
            $this->transformer->transform($role)
        );
    }
}
