<?php

declare(strict_types=1);

namespace App\Services\System\Role;

use App\Transformers\RoleTransformer;
use App\Helpers\PaginationHelper;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

class RoleService
{
    protected $transformer;

    public function __construct(RoleTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function createRole(array $inputData)
    {
        $role = Role::create($inputData);

        if (!$role) {
            throw new \Exception('Failed to create role');
        }

        return $role;
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

    public function updateRole(string $roleId, array $inputData)
    {
        $role = Role::find($roleId);

        if (!$role) {
            throw new \Exception('Role not found');
        }

        $role->update($inputData);

        return $role;
    }

    public function deleteRole($roleId)
    {
        $role = Role::find($roleId);

        if (!$role) {
            Log::info('Role not found with ID: ' . $roleId);
            throw new \Exception('Role not found');
        }

        $role->delete();
    }
}
