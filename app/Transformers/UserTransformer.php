<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\User;
use App\Traits\ExceptionHandlerTrait;

class UserTransformer
{
    use ExceptionHandlerTrait;

    public function transform(User $user): array
    {
        $isProfileRoute = request()->route()->getName() === 'readProfile';

        $data = [
            'type' => 'users',
            'id' => $user->id,
            'attributes' => [
                'id' => $user->id,
                'uid' => $user->uid,
                'username' => $user->username,
                'email' => $user->email,
                'roles' => $user->roles->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                    ];
                })->toArray(),
                'person' => [
                    'full_name' => $user->people?->full_name,
                ],
                'account' => $isProfileRoute ? $this->getAccountAttributes($user) : $this->getBasicAccountAttributes($user),
            ],
        ];

        if ($isProfileRoute) {
            $data['attributes']['menus'] = $user->getMenusForRole();
        }

        return $data;
    }

    private function getBasicAccountAttributes(User $user): array
    {
        return [
            'id' => $user->people?->account->id,
            'company_code' => $user->people?->account->company_code,
            'company_name' => $user->people?->account->company_name,
            'uid' => $user->people?->account->uid,
        ];
    }

    private function getAccountAttributes(User $user): array
    {
        return [
            'id' => $user->people?->account->id,
            'company_code' => $user->people?->account->company_code,
            'company_name' => $user->people?->account->company_name,
            'uid' => $user->people?->account->uid,
            'pit' => $user->people?->account?->pits->map(function ($pit) {
                return [
                    'id' => $pit->id,
                    'name' => $pit->name,
                    'description' => $pit->description,
                    'uid' => $pit->uid,
                ];
            })->toArray(),
            // 'permissions' => $user->people?->account?->getAllPermissions()->map(function ($permission) {
            //     return [
            //         'id' => $permission->id,
            //         'name' => $permission->name,
            //         'guard_name' => $permission->guard_name,
            //     ];
            // })->toArray(),
        ];
    }
}
