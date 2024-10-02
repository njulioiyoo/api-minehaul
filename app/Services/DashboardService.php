<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\PaginationHelper;
use App\Models\Trip;
use App\Traits\ExceptionHandlerTrait;
use App\Transformers\TripTransformer;

class DashboardService
{
    use ExceptionHandlerTrait;

    protected $transformer;

    protected $tripModel;

    public function __construct(TripTransformer $transformer, Trip $trip)
    {
        $this->transformer = $transformer;
        $this->tripModel = $trip;
    }

    public function readTrip(array $queryParams)
    {
        $perPage = $queryParams['page']['size'] ?? 10;
        $page = $queryParams['page']['number'] ?? 1;

        $query = $this->tripModel->query();

        if (isset($queryParams['filter'])) {
            foreach ($queryParams['filter'] as $field => $value) {
                $query->where($field, $value);
            }
        }

        $trips = $query->paginate($perPage, ['*'], 'page[number]', $page);

        $data = $trips->map(function ($role) {
            return $this->transformer->transform($role);
        })->values()->all(); // Convert to array

        return PaginationHelper::format($trips, $data);
    }
}
