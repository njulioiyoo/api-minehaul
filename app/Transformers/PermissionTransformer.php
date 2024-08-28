<?php

declare(strict_types=1);

namespace App\Transformers;

use Spatie\Permission\Models\Permission;

class PermissionTransformer
{
    public function transform(Permission $permission): array
    {
        return [
            'type' => 'permissions',
            'id' => $permission->id,
            'attributes' => [
                'id' => $permission->id,
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
                'created_at' => $permission->created_at,
                'updated_at' => $permission->updated_at,
            ],
        ];
    }
}
