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
        return [
            'type' => 'users',
            'id' => $user->id,
            'attributes' => [
                'id' => $user->id,
                'username' => $user->username,
                'person_id' => $user->person_id,
                'email' => $user->email,
                'roles' => $user->roles->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                        'permissions' => $role->permissions->map(function ($permission) {
                            return [
                                'id' => $permission->id,
                                'name' => $permission->name,
                            ];
                        })->toArray(),
                    ];
                })->toArray(),
                'menus' => $user->getMenusForRole(),
                'account' => $user->persons?->account,
                'pits' => $user->persons->account->pits,
            ],
        ];
    }
}
