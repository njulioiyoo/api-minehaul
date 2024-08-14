<?php

declare(strict_types=1);

namespace App\JsonApi\V1\System\Permission;

use LaravelJsonApi\Core\Resources\JsonApiResource;

class PermissionResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param  \Illuminate\Http\Request|null  $request
     */
    public function attributes($request): iterable
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
