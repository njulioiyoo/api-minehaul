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
                'account' => $user->people?->account,
            ],
        ];
    }
}
