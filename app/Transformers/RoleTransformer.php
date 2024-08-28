<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleTransformer
{
    public function transform(Role $role): array
    {
        return [
            'type' => 'users',
            'id' => $role->id,
            'attributes' => [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'created_at' => $role->created_at,
                'updated_at' => $role->updated_at,
                'permissions' => $role->permissions,
            ],
        ];
    }
}
