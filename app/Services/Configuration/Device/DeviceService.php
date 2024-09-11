<?php

declare(strict_types=1);

namespace App\Services\Configuration\Device;

use App\Helpers\PaginationHelper;
use App\Models\Device;
use App\Traits\ExceptionHandlerTrait;
use App\Transformers\DeviceTransformer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeviceService
{
    use ExceptionHandlerTrait;

    protected $transformer;

    protected $deviceModel; // Definisikan model device sekali di sini

    public function __construct(DeviceTransformer $transformer, Device $device)
    {
        $this->transformer = $transformer;
        $this->deviceModel = $device; // Simpan instance model di dalam properti
    }

    public function createDevice(array $inputData)
    {
        // dd($inputData);
        return DB::transaction(function () use ($inputData) {
            $device = $this->deviceModel->create($inputData);

            // Clear cache related to devices
            Cache::forget('device_'.$device->id);

            // Menggunakan transformer untuk format response JSON API
            return $this->formatJsonApiResponse(
                $this->transformer->transform($device)
            );
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
        // Menggunakan cache untuk mengambil device dengan UID yang diberikan
        $device = Cache::remember("device_$deviceUid", 60, function () use ($deviceUid) {
            return $this->deviceModel->where('uid', $deviceUid)->firstOrFail();
        });

        // Menggunakan transformer untuk format response JSON API
        return $this->formatJsonApiResponse(
            $this->transformer->transform($device)
        );
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

            // Menggunakan transformer untuk format response JSON API
            return $this->formatJsonApiResponse(
                $this->transformer->transform($device)
            );
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
