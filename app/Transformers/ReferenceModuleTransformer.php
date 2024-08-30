<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Device\DeviceIgnitionType;
use App\Models\Device\DeviceImmobilizitationType;
use App\Models\Device\DeviceMake;
use App\Models\Device\DeviceModel;
use App\Models\Device\DeviceStatus;
use App\Models\Device\DeviceType;
use Illuminate\Support\Collection;

class ReferenceModuleTransformer
{
    /**
     * Transforms the given models into a grouped array format.
     *
     * @return array
     */
    public function transform(): array
    {
        // Retrieve all necessary data in a single batch
        $status = DeviceStatus::select('id', 'name', 'status_theme')->get();
        $type = DeviceType::select('id', 'name')->get();
        $make = DeviceMake::with('deviceType')->select('id', 'device_type_id', 'name')->get();
        $models = DeviceModel::with('deviceMake')->select('id', 'device_make_id', 'name')->get();
        $immobilizitationType = DeviceImmobilizitationType::select('id', 'name')->get();
        $ignitionType = DeviceIgnitionType::select('id', 'name')->get();

        // Transform data model and its relations
        $transformedModels = $this->transformDeviceModels($models);

        return [
            'devices' => [
                'type' => 'devices',
                'attributes' => [
                    'device_status' => $status,
                    'device_type' => $type,
                    'device_make' => $make,
                    'device_model' => $transformedModels,
                    'device_immobilizitation_type' => $immobilizitationType,
                    'device_ignition_type' => $ignitionType
                ]
            ],
            'vehicle' => [
                'type' => 'vehicles',
                'attributes' => $this->transformVehicle()
            ],
        ];
    }

    /**
     * Transforms DeviceModel into an array format.
     *
     * @param Collection $models
     * @return array
     */
    protected function transformDeviceModels(Collection $models): array
    {
        return $models->map(function ($model) {
            return [
                'id' => $model->id,
                'name' => $model->name,
                'device_make' => $model->deviceMake ? [
                    'id' => $model->deviceMake->id,
                    'name' => $model->deviceMake->name,
                ] : null
            ];
        })->toArray(); // Convert collection to array for return
    }

    /**
     * Transforms Vehicle data into an array format.
     *
     * @return array
     */
    protected function transformVehicle(): array
    {
        return [
            'vehicle_status' => [],
            'vehicle_type' => [],
            'vehicle_make' => []
        ];
    }
}
