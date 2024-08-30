<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Device;

class DeviceTransformer
{
    /**
     * Transforms a Device model into an array format.
     *
     * @param Device $device
     * @return array
     */
    public function transform(Device $device): array
    {
        return [
            'type' => 'devices',
            'id' => $device->uid,
            'attributes' => [
                'id' => $device->uid,
                'account' => $this->transformRelation($device->account, ['id', 'company_code', 'company_name']),
                'pit' => $this->transformRelation($device->pit, ['id', 'name', 'description']),
                'device_type' => $this->transformRelation($device->deviceType, ['id', 'name']),
                'device_make' => $this->transformRelation($device->deviceMake, ['id', 'name']),
                'device_model' => $this->transformRelation($device->deviceModel, ['id', 'name']),
                'year' => $device->year,
                'display_id' => $device->display_id,
                'name' => $device->name,
                'sim_id' => $device->sim_id,
                'device_immobilizitation_type' => $this->transformRelation($device->deviceImmobilizitationType, ['id', 'name']),
                'device_ignition_type' => $this->transformRelation($device->deviceIgnitionType, ['id', 'name']),
                'status' => $device->status,
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
