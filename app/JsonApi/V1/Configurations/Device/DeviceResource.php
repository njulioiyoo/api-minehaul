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
            'account' => $this->account,
            'pit' => $this->pit,
            'device_type' => $this->deviceType,
            'device_make' => $this->deviceMake,
            'device_model' => $this->deviceModel,
            'display_id' => $this->display_id,
            'name' => $this->name,
            'sim_id' => $this->sim_id,
            'year' => $this->year,
            'status' => $this->status,
            'uid' => $this->uid,
        ];
    }
}
