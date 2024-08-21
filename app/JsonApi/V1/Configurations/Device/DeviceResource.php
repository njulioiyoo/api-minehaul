<?php

declare(strict_types=1);

namespace App\JsonApi\V1\Configurations\Device;

use LaravelJsonApi\Core\Resources\JsonApiResource;

class DeviceResource extends JsonApiResource
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
            'account_id' => $this->account_id,
            'name' => $this->name,
            'year' => $this->year,
            'status' => $this->status,
            'uid' => $this->uid,
        ];
    }
}
