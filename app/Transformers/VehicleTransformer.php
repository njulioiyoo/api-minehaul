<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Vehicle;
use App\Traits\ExceptionHandlerTrait;

class VehicleTransformer
{
    use ExceptionHandlerTrait;

    /**
     * Transforms a Device model into an array format.
     */
    public function transform(Vehicle $vehicle): array
    {
        return [
            'type' => 'vehicles',
            'id' => $vehicle->uid,
            'attributes' => [
                'id' => $vehicle->uid,
                'account' => $this->transformRelation($vehicle->account, ['company_code', 'company_name']),
                'pit' => $this->transformRelation($vehicle->pit, ['name', 'description']),
                'display_id' => $vehicle->display_id,
                'name' => $vehicle->name,
                'vin' => $vehicle->vin,
                'license_plate' => $vehicle->license_plate,
                'vehicle_type' => $this->transformRelation($vehicle->vehicleType, ['name']),
                'vehicle_make' => $this->transformRelation($vehicle->vehicleMake, ['name']),
                'vehicle_model' => $this->transformRelation($vehicle->vehicleModel, ['name']),
                'year' => $vehicle->year,
                'vehicle_status' => $this->transformRelation($vehicle->vehicleStatus, ['name']),
            ],
        ];
    }

    /**
     * Transforms a related model into an array format.
     *
     * @param  mixed  $relation
     */
    private function transformRelation($relation, array $fields): ?array
    {
        if (! $relation) {
            return null;
        }

        return $relation->only($fields);
    }
}
