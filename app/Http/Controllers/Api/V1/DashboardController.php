<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Traits\ExceptionHandlerTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    use ExceptionHandlerTrait;

    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Retrieve trip data based on query parameters.
     */
    public function readTrip(Request $request): JsonResponse
    {
        try {
            $queryParams = $request->query();
            $response = $this->dashboardService->readTrip($queryParams);

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->handleException($e, 'Error retrieving trip data');
        }
    }

    /**
     * Retrieve production data.
     */
    public function readProduction(): JsonResponse
    {
        try {
            $response = $this->dashboardService->readProduction();

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->handleException($e, 'Error retrieving production data');
        }
    }
}
