<?php

declare(strict_types=1);

namespace App\JsonApi\V1\System\Menu;

use LaravelJsonApi\Core\Resources\JsonApiResource;

class MenuResource extends JsonApiResource
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
            'icon' => $this->icon,
            'url' => $this->url,
            'parent_id' => $this->parent_id,
            'position' => $this->position,
            'roles' => $this->roles,
            'children' => $this->children,
        ];
    }
}
