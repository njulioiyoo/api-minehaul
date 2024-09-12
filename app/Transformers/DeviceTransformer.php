<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Device;

class DeviceTransformer
{
    /**
     * Transforms a Device model into an array format.
     */
    public function transform(Device $device): array
    {
        return [
            'type' => 'devices',
            'id' => $device->id,
            'attributes' => [
                'id' => $device->id,
                'account' => $this->transformRelation($device->account, ['id', 'uid', 'company_code', 'company_name']),
                'pit' => $this->transformRelation($device->pit, ['id', 'uid', 'name', 'description']),
                'device_type' => $this->transformRelation($device->deviceType, ['id', 'name']),
                'device_make' => $this->transformRelation($device->deviceMake, ['id', 'name']),
                'device_model' => $this->transformRelation($device->deviceModel, ['id', 'name']),
                'year' => $device->year,
                'display_id' => $device->display_id,
                'name' => $device->name,
                'sim_id' => $device->sim_id,
                'device_immobilizitation_type' => $this->transformRelation($device->deviceImmobilizitationType, ['name']),
                'device_ignition_type' => $this->transformRelation($device->deviceIgnitionType, ['id', 'name']),
                'device_status' => $this->transformRelation($device->deviceStatus, ['id', 'name']),
                'vehicle' => $this->transformRelation($device->vehicleId, ['id', 'name']),
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
