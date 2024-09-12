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
            'id' => $vehicle->id,
            'attributes' => [
                'id' => $vehicle->id,
                'uid' => $vehicle->uid,
                'account' => $this->transformRelation($vehicle->account, ['id', 'company_code', 'company_name']),
                'pit' => $this->transformRelation($vehicle->pit, ['id', 'name', 'description']),
                'display_id' => $vehicle->display_id,
                'name' => $vehicle->name,
                'vin' => $vehicle->vin,
                'license_plate' => $vehicle->license_plate,
                'vehicle_type' => $this->transformRelation($vehicle->vehicleType, ['id', 'name']),
                'vehicle_make' => $this->transformRelation($vehicle->vehicleMake, ['id', 'name']),
                'vehicle_model' => $this->transformRelation($vehicle->vehicleModel, ['id', 'name']),
                'year' => $vehicle->year,
                'vehicle_status' => $this->transformRelation($vehicle->vehicleStatus, ['id', 'name']),
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
