<?php

declare(strict_types=1);

namespace App\Services\Configuration\Device;

use App\Models\Device;
use App\Transformers\DeviceTransformer;
use App\Helpers\PaginationHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class DeviceService
{
    protected $transformer;
    protected $deviceModel; // Definisikan model device sekali di sini

    public function __construct(DeviceTransformer $transformer, Device $device)
    {
        $this->transformer = $transformer;
        $this->deviceModel = $device; // Simpan instance model di dalam properti
    }

    public function createDevice(array $inputData)
    {
        return DB::transaction(function () use ($inputData) {
            $device = $this->deviceModel->create($inputData);

            // Clear cache related to devices
            Cache::forget('device_' . $device->id);

            return $this->transformer->transform($device);
        });
    }

    public function readDevice(array $queryParams)
    {
        $perPage = $queryParams['page']['size'] ?? 10;
        $page = $queryParams['page']['number'] ?? 1;

        $query = $this->deviceModel->with(['account', 'pit', 'deviceType', 'deviceMake', 'deviceModel']);

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

    public function showDevice(string $deviceUid)
    {
        $device = Cache::remember("device_$deviceUid", 60, function () use ($deviceUid) {
            return $this->deviceModel->findOrFail($deviceUid);
        });

        return $this->transformer->transform($device);
    }

    public function updateDevice(string $deviceUid, array $inputData)
    {
        return DB::transaction(function () use ($deviceUid, $inputData) {
            $device = Cache::remember("device_$deviceUid", 60, function () use ($deviceUid) {
                return $this->deviceModel->findOrFail($deviceUid);
            });

            $device->update($inputData);

            // Update cache
            Cache::put("device_$deviceUid", $device, 60);

            return $this->transformer->transform($device);
        });
    }

    public function deleteDevice(string $deviceUid)
    {
        try {
            $device = Cache::remember("device_$deviceUid", 60, function () use ($deviceUid) {
                return $this->deviceModel->findOrFail($deviceUid);
            });

            $device->delete();

            // Clear cache
            Cache::forget("device_$deviceUid");
        } catch (\Throwable $th) {
            Log::error("Error deleting device with ID: {$deviceUid}, Error: {$th->getMessage()}");
            throw $th;
        }
    }
}
