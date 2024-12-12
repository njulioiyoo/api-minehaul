<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Trip;
use App\Services\Configuration\EntityCrudService;
use App\Transformers\TripTransformer;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    protected Trip $tripModel;

    protected TripTransformer $transformer;

    protected EntityCrudService $entityCrudService;

    public function __construct(Trip $tripModel, TripTransformer $transformer, EntityCrudService $entityCrudService)
    {
        $this->tripModel = $tripModel;
        $this->transformer = $transformer;
        $this->entityCrudService = $entityCrudService;
    }

    public function readTrip(array $queryParams)
    {
        // Pass $this->tripModel to EntityCrudService for generic handling
        return $this->entityCrudService->read(
            $this->tripModel,     // Use the injected model instance
            $queryParams,         // Query parameters
            $this->transformer,   // Transformer
            []                    // No additional relationships required
        );
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
