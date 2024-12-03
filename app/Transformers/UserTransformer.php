<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\User;
use App\Traits\ExceptionHandlerTrait;
use Illuminate\Support\Facades\DB;

class UserTransformer
{
    use ExceptionHandlerTrait;

    public function transform(User $user): array
    {
        // Mengambil data roles dan pits dari database
        $rolesData = DB::table('roles')
            ->join('role_has_pits', 'roles.id', '=', 'role_has_pits.role_id')
            ->join('accounts', 'role_has_pits.account_id', '=', 'accounts.id')
            ->join('pits', 'role_has_pits.pit_id', '=', 'pits.id')
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
            ->groupBy('role_id') // Mengelompokkan berdasarkan role_id
            ->map(function ($groupedRoles) {
                $role = $groupedRoles->first(); // Ambil role pertama dari grup

                return [
                    'id' => $role->role_id,
                    'name' => $role->role_name,
                ];
            })
            ->values()
            ->toArray(); // Reindex array hasil

        // Menyusun data akhir yang akan dikembalikan
        $data = [
            'type' => 'users',
            'id' => $user->id,
            'attributes' => [
                'id' => $user->id,
                'uid' => $user->uid,
                'username' => $user->username,
                'email' => $user->email,
                'roles' => $rolesData, // Menyertakan data roles yang sudah diproses
                'person' => [
                    'full_name' => $user->people?->full_name,
                ],
            ],
        ];

        return $data;
    }
}
