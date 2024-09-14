<?php

declare(strict_types=1);

namespace App\Services\Configuration\Driver;

use App\Helpers\PaginationHelper;
use App\Models\Driver;
use App\Transformers\DriverTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DriverService
{
    protected DriverTransformer $transformer;

    protected Driver $driverModel;

    public function __construct(DriverTransformer $transformer, Driver $driver)
    {
        $this->transformer = $transformer;
        $this->driverModel = $driver;
    }

    /**
     * Create a new driver in the database and clear related cache.
     *
     * @param  array  $inputData  Input data for creating the driver
     * @return JsonResponse Formatted JSON API response
     */
    public function createDriver(array $inputData): JsonResponse
    {
        return DB::transaction(function () use ($inputData) {
            $driver = $this->driverModel->create($inputData);

            // Clear cache related to the newly created driver
            Cache::forget('driver_'.$driver->id);

            // Return formatted JSON API response
            return $this->formatJsonApiResponse(
                $this->transformer->transform($driver)
            );
        });
    }

    /**
     * Read a list of drivers based on query parameters.
     *
     * @param  array  $queryParams  Query parameters for filtering and pagination
     * @return array Paginated and formatted driver data
     */
    public function readDriver(array $queryParams): array
    {
        $perPage = $queryParams['page']['size'] ?? 10;
        $page = $queryParams['page']['number'] ?? 1;

        $query = $this->driverModel->with(['account', 'pit']);

        if (isset($queryParams['filter'])) {
            foreach ($queryParams['filter'] as $field => $value) {
                $query->where($field, $value);
            }
        }

        $drivers = $query->paginate($perPage, ['*'], 'page[number]', $page);

        $data = $drivers->map(fn ($driver) => $this->transformer->transform($driver))->values()->all();

        return PaginationHelper::format($drivers, $data);
    }

    /**
     * Show the details of a driver by ID.
     *
     * @param  int  $driverId  ID of the driver to be displayed
     * @return JsonResponse Formatted JSON API response
     *
     * @throws ModelNotFoundException If the driver is not found
     */
    public function showDriver(int $driverId): JsonResponse
    {
        $driver = Cache::remember("driver_$driverId", 60, function () use ($driverId) {
            return $this->driverModel->find($driverId);
        });

        if (! $driver) {
            throw new ModelNotFoundException('Driver not found');
        }

        return $this->formatJsonApiResponse(
            $this->transformer->transform($driver)
        );
    }

    /**
     * Update the data of a driver by ID.
     *
     * @param  int  $driverId  ID of the driver to be updated
     * @param  array  $inputData  Input data for the update
     * @return JsonResponse Formatted JSON API response
     *
     * @throws ModelNotFoundException If the driver is not found
     */
    public function updateDriver(int $driverId, array $inputData): JsonResponse
    {
        return DB::transaction(function () use ($driverId, $inputData) {
            $this->driverModel->where('id', $driverId)->update($inputData);

            $driver = $this->driverModel->find($driverId);

            if ($driver) {
                Cache::put("driver_$driverId", $driver, 60);

                return $this->formatJsonApiResponse(
                    $this->transformer->transform($driver)
                );
            }

            throw new ModelNotFoundException('Driver not found');
        });
    }

    /**
     * Delete a driver by ID.
     *
     * @param  int  $driverId  ID of the driver to be deleted
     * @return JsonResponse JSON response confirming the deletion
     *
     * @throws \Throwable If an error occurs during deletion
     */
    public function deleteDriver(int $driverId): JsonResponse
    {
        try {
            $driver = $this->driverModel->find($driverId);

            if (! $driver) {
                throw new ModelNotFoundException('Driver not found');
            }

            $driver->delete();

            Cache::forget("driver_$driverId");

            return response()->json(['message' => 'Driver deleted successfully']);
        } catch (\Throwable $th) {
            Log::error("Error deleting driver with ID: {$driverId}, Error: {$th->getMessage()}");
            throw $th;
        }
    }

    /**
     * Format the JSON API response.
     *
     * @param  mixed  $data  Data to be formatted
     * @return JsonResponse Formatted JSON API response
     */
    protected function formatJsonApiResponse($data): JsonResponse
    {
        return response()->json($data);
    }
}
