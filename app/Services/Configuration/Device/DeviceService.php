<?php

declare(strict_types=1);

namespace App\Services\Configuration\Device;

use App\Helpers\PaginationHelper;
use App\Models\Device;
use App\Traits\ExceptionHandlerTrait;
use App\Transformers\DeviceTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeviceService
{
    use ExceptionHandlerTrait;

    // Properties to store transformer and device model
    protected DeviceTransformer $transformer;

    protected Device $deviceModel;

    // Constructor for dependency injection
    public function __construct(DeviceTransformer $transformer, Device $device)
    {
        $this->transformer = $transformer;
        $this->deviceModel = $device;
    }

    /**
     * Create a new device in the database and clear related cache.
     *
     * @param  array  $inputData  Input data for creating the device
     * @return mixed Formatted JSON API response
     */
    public function createDevice(array $inputData)
    {
        return DB::transaction(function () use ($inputData) {
            // Create a new device within a transaction
            $device = $this->deviceModel->create($inputData);

            // Forget cache for the newly created device
            Cache::forget("device_{$device->uid}");

            // Return formatted JSON API response
            return $this->formatJsonApiResponse(
                $this->transformer->transform($device)
            );
        });
    }

    /**
     * Read a list of devices based on query parameters.
     *
     * @param  array  $queryParams  Query parameters for filtering and pagination
     * @return array Paginated and formatted device data
     */
    public function readDevice(array $queryParams)
    {
        $perPage = $queryParams['page']['size'] ?? 10;
        $page = $queryParams['page']['number'] ?? 1;

        $query = $this->deviceModel->with(['account', 'pit', 'deviceType', 'deviceMake', 'deviceModel']);

        // Apply filters if any
        if (isset($queryParams['filter'])) {
            foreach ($queryParams['filter'] as $field => $value) {
                $query->where($field, $value);
            }
        }

        // Get paginated device data
        $devices = $query->paginate($perPage, ['*'], 'page[number]', $page);

        // Transform device data using the transformer
        $data = $devices->map(fn ($device) => $this->transformer->transform($device))->values()->all();

        // Return paginated data with formatting
        return PaginationHelper::format($devices, $data);
    }

    /**
     * Show the details of a device by UID.
     *
     * @param  string  $deviceUid  UID of the device to be displayed
     * @return mixed Formatted JSON API response
     *
     * @throws ModelNotFoundException If the device is not found
     */
    public function showDevice(string $deviceUid)
    {
        // Retrieve device from cache or database
        $device = Cache::remember("device_$deviceUid", 60, function () use ($deviceUid) {
            return $this->deviceModel->where('uid', $deviceUid)->first();
        });

        // If the device is not found, throw exception
        if (! $device) {
            throw new ModelNotFoundException('Device not found');
        }

        // Return formatted JSON API response
        return $this->formatJsonApiResponse(
            $this->transformer->transform($device)
        );
    }

    /**
     * Update the data of a device by UID.
     *
     * @param  string  $deviceUid  UID of the device to be updated
     * @param  array  $inputData  Input data for the update
     * @return mixed Formatted JSON API response
     *
     * @throws ModelNotFoundException If the device is not found
     */
    public function updateDevice(string $deviceUid, array $inputData)
    {
        return DB::transaction(function () use ($deviceUid, $inputData) {
            // Update the device data
            $this->deviceModel->where('uid', $deviceUid)->update($inputData);

            // Retrieve the updated device
            $device = $this->deviceModel->where('uid', $deviceUid)->first();

            // If the device is not found, throw exception
            if ($device) {
                // Update cache with the latest data
                Cache::put("device_$deviceUid", $device, 60);

                // Return formatted JSON API response
                return $this->formatJsonApiResponse(
                    $this->transformer->transform($device)
                );
            }

            throw new ModelNotFoundException('Device not found');
        });
    }

    /**
     * Delete a device by UID.
     *
     * @param  string  $deviceUid  UID of the device to be deleted
     * @return mixed JSON response confirming the deletion
     *
     * @throws \Throwable If an error occurs during deletion
     */
    public function deleteDevice(string $deviceUid)
    {
        try {
            // Find the device before deleting
            $device = $this->deviceModel->where('uid', $deviceUid)->first();

            // If the device is not found, throw exception
            if (! $device) {
                throw new ModelNotFoundException('Device not found');
            }

            // Delete the device
            $device->delete();

            // Forget cache for the deleted device
            Cache::forget("device_$deviceUid");

            // Return JSON response confirming the deletion
            return response()->json(['message' => 'Device deleted successfully']);
        } catch (\Throwable $th) {
            // Log the error
            Log::error("Error deleting device with UID: {$deviceUid}, Error: {$th->getMessage()}");
            throw $th;
        }
    }
}
