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
            'id' => $this->uid,
            'account' => $this->account,
            'pit' => $this->pit,
            'device_type' => $this->deviceType,
            'device_make' => $this->deviceMake,
            'device_model' => $this->deviceModel,
            'year' => $this->year,
            'display_id' => $this->display_id,
            'name' => $this->name,
            'sim_id' => $this->sim_id,
            'device_immobilizitation_type_id' => $this->device_immobilizitation_type_id,
            'device_ignition_type_id' => $this->device_ignition_type_id,
            'device_status_id' => $this->device_status_id,
            'status' => $this->status
        ];
    }
}
