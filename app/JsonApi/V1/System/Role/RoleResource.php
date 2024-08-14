<?php

declare(strict_types=1);

namespace App\JsonApi\V1\System\Role;

use LaravelJsonApi\Core\Resources\JsonApiResource;

class RoleResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param  \Illuminate\Http\Request|null  $request
     */
    public function attributes($request): iterable
    {
        return [
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'permissions' => $this->permissions,
        ];
    }
}
