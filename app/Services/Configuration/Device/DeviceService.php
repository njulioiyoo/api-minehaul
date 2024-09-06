<?php

declare(strict_types=1);

namespace App\Services\Configuration\Device;

use App\Models\Device;
use App\Transformers\DeviceTransformer;
use App\Helpers\PaginationHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeviceService
{
    protected $transformer;

    public function __construct(DeviceTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function createDevice(array $inputData)
    {
        return DB::transaction(function () use ($inputData) {
            $device = Device::create($inputData);

            return $this->transformer->transform($device);
        });
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
        return DB::transaction(function () use ($deviceUid, $inputData) {
            $device = Device::findOrFail($deviceUid);

            $device->update($inputData);

            return $this->transformer->transform($device);
        });
    }

    public function deleteDevice(string $deviceUid)
    {
        try {
            $device = Device::findOrFail($deviceUid);

            $device->delete();
        } catch (\Throwable $th) {
            Log::error("Error deleting device with ID: {$deviceUid}, Error: {$th->getMessage()}");
            throw $th;
        }
    }
}
