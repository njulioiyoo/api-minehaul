<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Device;

class DeviceTransformer
{
    public function transform(Device $device): array
    {
        return [
            'type' => 'devices',
            'id' => $device->uid,
            'attributes' => [
                'id' => $device->id,
                'account' => $device->account ? [
                    'id' => $device->account->id,
                    'company_code' => $device->account->company_code,
                    'company_name' => $device->account->company_name,
                ] : null,
                'pit' => $device->pit ? [
                    'id' => $device->pit->id,
                    'name' => $device->pit->name,
                    'description' => $device->pit->description,
                ] : null,
                'device_type' => $device->deviceType ? [
                    'id' => $device->deviceType->id,
                    'name' => $device->deviceType->name,
                ] : null,
                'device_make' => $device->deviceMake ? [
                    'id' => $device->deviceMake->id,
                    'name' => $device->deviceMake->name,
                ] : null,
                'device_model' => $device->deviceModel ? [
                    'id' => $device->deviceModel->id,
                    'name' => $device->deviceModel->name,
                ] : null,
                'display_id' => $device->display_id,
                'name' => $device->name,
                'sim_id' => $device->sim_id,
                'year' => $device->year,
                'status' => $device->status,
                'uid' => $device->uid,
            ]
        ];
    }
}
