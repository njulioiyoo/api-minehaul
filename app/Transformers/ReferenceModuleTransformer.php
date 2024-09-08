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

class ReferenceModuleTransformer
{
    /**
     * Transforms the requested device data or returns all data if no type is specified.
     *
     * @param string|null $type
     * @return array
     */
    public function transformDevice($type = null): array
    {
        $status = DeviceStatus::select('id', 'name', 'status_theme')->get();
        $types = DeviceType::select('id', 'name')->get();
        $makes = DeviceMake::with('deviceType')->select('id', 'device_type_id', 'name')->get();
        $models = DeviceModel::with('deviceMake')->select('id', 'device_make_id', 'name')->get();
        $immobilizitationTypes = DeviceImmobilizitationType::select('id', 'name')->get();
        $ignitionTypes = DeviceIgnitionType::select('id', 'name')->get();

        $transformedModels = $models->map(function ($model) {
            return [
                'id' => $model->id,
                'name' => $model->name,
                'device_make' => $model->deviceMake ? [
                    'id' => $model->deviceMake->id,
                    'name' => $model->deviceMake->name,
                ] : null
            ];
        })->toArray();

        // Data lengkap yang akan dikembalikan
        $data = [
            'device_status' => $status,
            'device_type' => $types,
            'device_make' => $makes,
            'device_model' => $transformedModels,
            'device_immobilizitation_type' => $immobilizitationTypes,
            'device_ignition_type' => $ignitionTypes
        ];

        // Jika ada tipe yang diminta, kembalikan data tersebut saja
        return $type ? [$type => $data[$type]] : $data;
    }

    /**
     * Transforms the requested vehicle data or returns all data if no type is specified.
     *
     * @param string|null $type
     * @return array
     */
    public function transformVehicle($type = null): array
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
        })->toArray();

        $transformedModel = $models->map(function ($model) {
            return [
                'id' => $model->id,
                'name' => $model->name,
                'vehicle_model' => $model->vehicleMake ? [
                    'id' => $model->vehicleMake->id,
                    'name' => $model->vehicleMake->name,
                ] : null
            ];
        })->toArray();

        // Data lengkap yang akan dikembalikan
        $data = [
            'vehicle_status' => $status,
            'vehicle_type' => $types,
            'vehicle_make' => $transformedMakes,
            'vehicle_model' => $transformedModel
        ];

        // Jika ada tipe yang diminta, kembalikan data tersebut saja
        return $type ? [$type => $data[$type]] : $data;
    }
}
