<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function readTrip(Request $request)
    {
        try {
            $queryParams = $request->query();
            $response = $this->dashboardService->readTrip($queryParams);

            return response()->json($response);
        } catch (\Exception $e) {
        }
    }

    public function readProduction()
    {
        try {
            $response = $this->dashboardService->readProduction();

            return response()->json($response);
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
