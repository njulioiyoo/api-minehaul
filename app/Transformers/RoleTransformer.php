<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Traits\ExceptionHandlerTrait;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleTransformer
{
    use ExceptionHandlerTrait;

    public function transform(Role $role): array
    {
        $isRoleAccessRoute = request()->route()->getName() === 'roleAccess';

        // Mengambil data roles terkait
        $rolesData = $this->getRolesData($role, $isRoleAccessRoute);

        if (empty($rolesData)) {
            return [];
        }

        // Menyusun data role
        $data = [
            'type' => 'roles',
            'id' => $role->id,
            'attributes' => [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'created_at' => $role->created_at,
                'updated_at' => $role->updated_at,
                'permissions' => $this->getPermissions($role),
            ],
        ];

        if ($isRoleAccessRoute) {
            $user = auth()->user();
            $data['attributes']['accounts'] = $rolesData;
            $data['attributes']['menus'] = $user->getMenusForRole();
        }

        return $data;
    }

    /**
     * Mengambil data roles terkait berdasarkan kondisi
     */
    private function getRolesData(Role $role, bool $isRoleAccessRoute): array
    {
        $baseQuery = DB::table('roles')
            ->join('role_has_pits', 'roles.id', '=', 'role_has_pits.role_id')
            ->join('accounts', 'role_has_pits.account_id', '=', 'accounts.id')
            ->join('pits', 'role_has_pits.pit_id', '=', 'pits.id')
            ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.id', '=', $role->id);

        if ($isRoleAccessRoute) {
            $result = $baseQuery
                ->where('model_has_roles.model_id', '=', auth()->user()->id)
                ->first($this->getSelectFields());

            // Konversi objek menjadi array
            return $result ? (array) $result : [];
        }

        $result = $baseQuery
            ->groupBy('account_id')
            ->get($this->getSelectFields());

        // Konversi Collection menjadi array
        return $result->map(function ($groupedAccounts) {
            $account = $groupedAccounts->first();

            return [
                'id' => $account->account_id,
                'company_code' => $account->company_code,
                'company_name' => $account->company_name,
                'uid' => $account->account_uid,
                'pits' => $groupedAccounts->map(function ($item) {
                    return [
                        'id' => $item->pit_id,
                        'name' => $item->pit_name,
                        'description' => $item->pit_description,
                        'uid' => $item->pit_uid,
                    ];
                })->toArray(),
            ];
        })
            ->values()
            ->toArray();
    }

    /**
     * Mendapatkan permissions terkait role
     */
    private function getPermissions(Role $role): array
    {
        return $role->permissions->map(function ($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
                'created_at' => $permission->created_at,
                'updated_at' => $permission->updated_at,
                'pivot' => [
                    'role_id' => $permission->pivot->role_id,
                    'permission_id' => $permission->pivot->permission_id,
                ],
            ];
        })->toArray();
    }

    /**
     * Mendapatkan daftar kolom yang akan diambil dari database
     */
    private function getSelectFields(): array
    {
        return [
            'roles.id as role_id',
            'roles.name as role_name',
            'accounts.id as account_id',
            'accounts.company_code',
            'accounts.company_name',
            'accounts.uid as account_uid',
            'pits.id as pit_id',
            'pits.name as pit_name',
            'pits.description as pit_description',
            'pits.uid as pit_uid',
        ];
    }
}
