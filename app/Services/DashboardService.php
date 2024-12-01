<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\PaginationHelper;
use App\Models\Trip;
use App\Traits\ExceptionHandlerTrait;
use App\Transformers\TripTransformer;
use Illuminate\Support\Facades\DB;

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

    public function readProduction()
    {
        $user = auth()->user();
        $account = $user?->people?->account;

        return DB::table('trips AS t')
            ->leftJoin('trip_load_scanners AS tls', 'tls.id', '=', 't.trip_load_scanner_id')
            ->leftJoin('trip_types AS tt', 'tt.id', '=', 't.trip_type_id')
            ->selectRaw("TO_CHAR(tls.created_at, 'dd Mon') AS name")
            ->selectRaw('ROUND(SUM(CASE WHEN t.trip_type_id = 1 THEN COALESCE(t.quantity, 0) ELSE 0 END), 2) AS overburden')
            ->selectRaw('ROUND(SUM(CASE WHEN t.trip_type_id = 2 THEN COALESCE(t.quantity, 0) ELSE 0 END), 2) AS coal')
            ->where('t.account_id', $account->id)
            ->groupBy(DB::raw("TO_CHAR(tls.created_at, 'dd Mon')"))
            ->orderBy(DB::raw("TO_CHAR(tls.created_at, 'dd Mon')"), 'asc')
            ->get();
    }
}
