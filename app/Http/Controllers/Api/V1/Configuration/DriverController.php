<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Configuration;

use App\Http\Controllers\Controller;
use App\Services\Configuration\Driver\DriverService;
use App\Services\RequestHelperService;
use App\Traits\ExceptionHandlerTrait;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    use ExceptionHandlerTrait;

    protected $driverService;

    protected $requestHelperService;

    public function __construct(DriverService $driverService, RequestHelperService $requestHelperService)
    {
        $this->driverService = $driverService;
        $this->requestHelperService = $requestHelperService;
    }

    public function readDriver(Request $request)
    {
        try {
            $queryParams = $request->query();
            $response = $this->driverService->readDriver($queryParams);

            return response()->json($response);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error reading drivers');
        }
    }
}
