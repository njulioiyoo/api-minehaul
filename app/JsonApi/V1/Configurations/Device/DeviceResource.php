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
            'device_immobilizitation_type' => $this->deviceImmobilizitationType,
            'device_ignition_type' => $this->deviceIgnitionType,
            'device_status' => $this->deviceStatus,
            'vehicle' => $this->vehicle_id,
        ];
    }
}
