<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Vehicle;

class VehicleTransformer
{
    /**
     * Transforms a Device model into an array format.
     *
     * @param Vehicle $vehicle
     * @return array
     */
    public function transform(Vehicle $vehicle): array
    {
        return [
            'type' => 'vehicles',
            'id' => $vehicle->uid,
            'attributes' => [
                'id' => $vehicle->uid,
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
            ]
        ];
    }

    /**
     * Transforms a related model into an array format.
     *
     * @param mixed $relation
     * @param array $fields
     * @return array|null
     */
    private function transformRelation($relation, array $fields): ?array
    {
        if (!$relation) {
            return null;
        }

        return $relation->only($fields);
    }
}
