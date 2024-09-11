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
     * Returns an array of queries for device-related data.
     */
    protected function getDeviceQueries(): array
    {
        return [
            'device_status' => fn () => DeviceStatus::select('id', 'name', 'status_theme')->get(),
            'device_type' => fn () => DeviceType::select('id', 'name')->get(),
            'device_make' => fn () => DeviceMake::with('deviceType')->select('id', 'device_type_id', 'name')->get(),
            'device_model' => fn () => DeviceModel::with('deviceMake')->select('id', 'device_make_id', 'name')->get()->map(function ($model) {
                return [
                    'id' => $model->id,
                    'name' => $model->name,
                    'device_make' => $model->deviceMake ? [
                        'id' => $model->deviceMake->id,
                        'name' => $model->deviceMake->name,
                    ] : null,
                ];
            })->toArray(),
            'device_immobilizitation_type' => fn () => DeviceImmobilizitationType::select('id', 'name')->get(),
            'device_ignition_type' => fn () => DeviceIgnitionType::select('id', 'name')->get(),
        ];
    }

    /**
     * Returns an array of queries for vehicle-related data.
     */
    protected function getVehicleQueries(): array
    {
        return [
            'vehicle_status' => fn () => VehicleStatus::select('id', 'name')->get(),
            'vehicle_type' => fn () => VehicleType::select('id', 'name')->get(),
            'vehicle_make' => fn () => VehicleMake::with('vehicleType')->select('id', 'vehicle_type_id', 'name')->get()->map(function ($make) {
                return [
                    'id' => $make->id,
                    'name' => $make->name,
                    'vehicle_type' => $make->vehicleType ? [
                        'id' => $make->vehicleType->id,
                        'name' => $make->vehicleType->name,
                    ] : null,
                ];
            })->toArray(),
            'vehicle_model' => fn () => VehicleModel::with('vehicleMake')->select('id', 'vehicle_make_id', 'name')->get()->map(function ($model) {
                return [
                    'id' => $model->id,
                    'name' => $model->name,
                    'vehicle_make' => $model->vehicleMake ? [
                        'id' => $model->vehicleMake->id,
                        'name' => $model->vehicleMake->name,
                    ] : null,
                ];
            })->toArray(),
        ];
    }

    /**
     * Transforms the requested device data or returns all data if no type is specified.
     *
     * @param  string|null  $type
     */
    public function transformDevice($type = null): array
    {
        $data = [];
        $queries = $this->getDeviceQueries();
        $types = array_filter($queries, fn ($key) => str_starts_with($key, 'device_'), ARRAY_FILTER_USE_KEY);

        if ($type === null || $type === '') {
            foreach ($types as $key => $query) {
                $data[$key] = $query();
            }
        } elseif (array_key_exists($type, $types)) {
            $data[$type] = $types[$type]();
        }

        return $data;
    }

    /**
     * Transforms the requested vehicle data or returns all data if no type is specified.
     *
     * @param  string|null  $type
     */
    public function transformVehicle($type = null): array
    {
        $data = [];
        $queries = $this->getVehicleQueries();
        $types = array_filter($queries, fn ($key) => str_starts_with($key, 'vehicle_'), ARRAY_FILTER_USE_KEY);

        if ($type === null || $type === '') {
            foreach ($types as $key => $query) {
                $data[$key] = $query();
            }
        } elseif (array_key_exists($type, $types)) {
            $data[$type] = $types[$type]();
        }

        return $data;
    }
}
