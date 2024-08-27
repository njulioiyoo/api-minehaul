<?php

declare(strict_types=1);

namespace App\Services\Configuration\Device;

use App\Models\Device;
use App\Transformers\DeviceTransformer;
use App\Helpers\PaginationHelper;

class DeviceService
{
    protected $transformer;

    public function __construct(DeviceTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function createDevice(array $inputData)
    {
        $device = Device::create($inputData);

        if (!$device) {
            throw new \Exception('Failed to create device');
        }

        return $device;
    }

    public function readDevice(array $queryParams)
    {
        $perPage = $queryParams['page']['size'] ?? 10;
        $page = $queryParams['page']['number'] ?? 1;

        $query = Device::with(['account', 'pit', 'deviceType', 'deviceMake', 'deviceModel']);

        if (isset($queryParams['filter'])) {
            foreach ($queryParams['filter'] as $field => $value) {
                $query->where($field, $value);
            }
        }

        $devices = $query->paginate($perPage, ['*'], 'page[number]', $page);

        $data = $devices->map(function ($device) {
            return $this->transformer->transform($device);
        })->values()->all(); // Convert to array

        return PaginationHelper::format($devices, $data);
    }

    public function updateDevice(string $deviceUid, array $inputData)
    {
        $device = Device::find($deviceUid);

        if (!$device) {
            throw new \Exception('Device not found');
        }

        $device->update($inputData);

        return $device;
    }

    public function deleteDevice(string $deviceUid)
    {
        $device = Device::find($deviceUid);

        if (!$device) {
            throw new \Exception('Device not found');
        }

        $device->delete();
    }
}
