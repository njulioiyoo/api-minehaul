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
        // Mengambil data terkait account dan pits yang berhubungan dengan role
        $rolesData = DB::table('roles')
            ->join('role_has_pits', 'roles.id', '=', 'role_has_pits.role_id')
            ->join('accounts', 'role_has_pits.account_id', '=', 'accounts.id')
            ->join('pits', 'role_has_pits.pit_id', '=', 'pits.id')
            ->where('roles.id', '=', $role->id)
            ->get([
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
            ])
            ->groupBy('account_id') // Mengelompokkan berdasarkan account_id
            ->map(function ($groupedAccounts) {
                $account = $groupedAccounts->first(); // Ambil account pertama dari grup

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
                    })->toArray(), // Mengambil semua pits terkait
                ];
            })
            ->values()
            ->toArray(); // Reindex array hasil

        return [
            'type' => 'roles',
            'id' => $role->id,
            'attributes' => [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'created_at' => $role->created_at,
                'updated_at' => $role->updated_at,
                'permissions' => $role->permissions->map(function ($permission) {
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
                })->toArray(), // Mengambil semua permissions terkait role
            ],
            'account' => $rolesData, // Menyertakan data account yang berisi pits
        ];
    }
}
