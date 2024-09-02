<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Device\DeviceIgnitionType;
use App\Models\Device\DeviceImmobilizitationType;
use App\Models\Device\DeviceMake;
use App\Models\Device\DeviceModel;
use App\Models\Device\DeviceStatus;
use App\Models\Device\DeviceType;
use App\Models\Vehicle\VehicleMake;
use App\Models\Vehicle\VehicleModel;
use App\Models\Vehicle\VehicleStatus;
use App\Models\Vehicle\VehicleType;
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
        $types = DeviceType::select('id', 'name')->get();
        $makes = DeviceMake::with('deviceType')->select('id', 'device_type_id', 'name')->get();
        $models = DeviceModel::with('deviceMake')->select('id', 'device_make_id', 'name')->get();
        $immobilizitationTypes = DeviceImmobilizitationType::select('id', 'name')->get();
        $ignitionTypes = DeviceIgnitionType::select('id', 'name')->get();

        // Transform data model and its relations
        $transformedModels = $this->transformDeviceModels($models);

        return [
            'devices' => [
                'type' => 'devices',
                'attributes' => [
                    'device_status' => $status,
                    'device_type' => $types,
                    'device_make' => $makes,
                    'device_model' => $transformedModels,
                    'device_immobilizitation_type' => $immobilizitationTypes,
                    'device_ignition_type' => $ignitionTypes
                ]
            ],
            'vehicles' => [
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
        $status = VehicleStatus::select('id', 'name')->get();
        $types = VehicleType::select('id', 'name')->get();
        $makes = VehicleMake::with('vehicleType')->select('id', 'vehicle_type_id', 'name')->get();
        $models = VehicleModel::with('vehicleMake')->select('id', 'vehicle_make_id', 'name')->get();

        $transformedMakes = $makes->map(function ($make) {
            return [
                'id' => $make->id,
                'name' => $make->name,
                'vehicle_type' => $make->vehicleType ? [
                    'id' => $make->vehicleType->id,
                    'name' => $make->vehicleType->name,
                ] : null
            ];
        });

        $transformedModel = $models->map(function ($model) {
            return [
                'id' => $model->id,
                'name' => $model->name,
                'vehicle_model' => $model->vehicleMake ? [
                    'id' => $model->vehicleMake->id,
                    'name' => $model->vehicleMake->name,
                ] : null
            ];
        });

        return [
            'vehicle_status' => $status,
            'vehicle_type' => $types,
            'vehicle_make' => $transformedMakes,
            'vehicle_model' => $transformedModel
        ];
    }
}
