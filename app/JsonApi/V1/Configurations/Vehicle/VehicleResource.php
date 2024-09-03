<?php

declare(strict_types=1);

namespace App\JsonApi\V1\Configurations\Vehicle;

use LaravelJsonApi\Core\Resources\JsonApiResource;

class VehicleResource extends JsonApiResource
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
            'year' => $this->year,
            'display_id' => $this->display_id,
            'name' => $this->name,
            'vin' => $this->vin,
            'license_plate' => $this->license_plate,
            'vehicle_type' => $this->vehicleType,
            'vehicle_make' => $this->vehicleMake,
            'vehicle_model' => $this->vehicleModel,
            'vehicle_status' => $this->vehicleStatus,
        ];
    }
}
