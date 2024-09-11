<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Traits\ExceptionHandlerTrait;
use Spatie\Permission\Models\Role;

class RoleTransformer
{
    use ExceptionHandlerTrait;

    public function transform(Role $role): array
    {
        return [
            'type' => 'roles',
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
