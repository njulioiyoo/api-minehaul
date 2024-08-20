<?php

declare(strict_types=1);

namespace App\JsonApi\V1\System\Users;

use LaravelJsonApi\Core\Resources\JsonApiResource;

class UserResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param  \Illuminate\Http\Request|null  $request
     */
    public function attributes($request): iterable
    {
        return [
            'username' => $this->username,
            'person_id' => $this->person_id,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'roles' => $this->roles->map(fn ($role) => $this->transformRole($role)),
            'menus' => $this->getMenusForRole(),
            'account' => $this->persons?->account,

        ];
    }

    /**
     * Transform a role to include its permissions.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     */
    protected function transformRole($role): array
    {
        return [
            'id' => $role->id,
            'name' => $role->name,
            'guard_name' => $role->guard_name,
            'created_at' => $role->created_at,
            'updated_at' => $role->updated_at,
            'permissions' => $role->permissions->map(fn ($permission) => [
                'id' => $permission->id,
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
                'created_at' => $permission->created_at,
                'updated_at' => $permission->updated_at,
            ]),
        ];
    }
}
