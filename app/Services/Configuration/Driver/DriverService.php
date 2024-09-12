<?php

declare(strict_types=1);

namespace App\Services\Configuration\Driver;

use App\Helpers\PaginationHelper;
use App\Models\Driver;
use App\Transformers\DriverTransformer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DriverService
{
    protected $transformer;

    protected $driverModel;

    public function __construct(DriverTransformer $transformer, Driver $driver)
    {
        $this->transformer = $transformer;
        $this->driverModel = $driver;
    }

    public function createDriver(array $inputData)
    {
        return DB::transaction(function () use ($inputData) {
            $driver = $this->driverModel->create($inputData);

            // Clear cache related to devices
            Cache::forget('driver_'.$driver->id);

            // Menggunakan transformer untuk format response JSON API
            // return $this->formatJsonApiResponse(
            //     $this->transformer->transform($driver)
            // );
        });
    }

    public function readDriver(array $queryParams)
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

        $data = $drivers->map(function ($driver) {
            return $this->transformer->transform($driver);
        })->values()->all(); // Convert to array

        return PaginationHelper::format($drivers, $data);
    }
}
