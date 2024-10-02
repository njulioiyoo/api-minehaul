<?php

declare(strict_types=1);

namespace App\Services\System\Role;

use App\Helpers\PaginationHelper;
use App\Models\RoleHasPit;
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
            // Create a new role
            $role = $this->roleModel->create($inputData);

            // Clear any cache related to this role
            Cache::forget('role_'.$role->id);

            // If there are permissions provided, sync them with the role
            if (! empty($permissions)) {
                $role->syncPermissions($permissions);
            }

            // Get account_id from the input data
            $accountId = $inputData['account_id'];

            // Get list of pit_ids from the input data
            $pitIds = $inputData['pit_id'];

            // Save the data into the role_has_pits table using the model
            foreach ($pitIds as $pitId) {
                RoleHasPit::create([
                    'role_id' => $role->id,
                    'account_id' => $accountId,
                    'pit_id' => $pitId,
                ]);
            }

            // Return the response in JSON API format
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
            // Find the role or fail if it doesn't exist
            $role = $this->roleModel->findOrFail($roleId);

            // Update the role with the new input data
            $role->update($inputData);

            // Update the cache for the role
            Cache::put("role_$roleId", $role, 60);

            // If there are permissions, sync them with the role
            if (! empty($permissions)) {
                $role->syncPermissions($permissions);
            }

            // Get account_id from the input data
            $accountId = $inputData['account_id'];

            // Get list of pit_ids from the input data
            $pitIds = $inputData['pit_id'];

            // Remove old entries from role_has_pits and add the new ones
            // First, delete all current role_has_pits for the role
            RoleHasPit::where('role_id', $role->id)->delete();

            // Then insert the new account_id and pit_id mappings
            foreach ($pitIds as $pitId) {
                RoleHasPit::create([
                    'role_id' => $role->id,
                    'account_id' => $accountId,
                    'pit_id' => $pitId,
                ]);
            }

            // Return the response in JSON API format using a transformer
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
