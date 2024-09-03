<?php

namespace App\Http\Controllers\Api\V1\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\Vehicle\StoreVehicleRequest;
use App\Services\Configuration\Vehicle\VehicleService;
use Illuminate\Http\Request;
use App\Services\RequestHelperService;
use Illuminate\Support\Facades\Log;
use LaravelJsonApi\Core\Responses\ErrorResponse;
use LaravelJsonApi\Core\Document\Error;
use LaravelJsonApi\Core\Responses\DataResponse;

class VehicleController extends Controller
{
    protected $requestHelperService;
    protected $vehicleService;

    public function __construct(RequestHelperService $requestHelperService, VehicleService $vehicleService)
    {
        $this->requestHelperService = $requestHelperService;
        $this->vehicleService = $vehicleService;
    }

    public function createVehicle(StoreVehicleRequest $request)
    {
        $validatedData = $request->validated();
        $vehicle = $this->vehicleService->createVehicle($validatedData);

        // return new DataResponse($vehicle);
        return response()->json($vehicle);
    }

    public function readVehicle(Request $request)
    {
        $queryParams = $request->query();

        try {
            $response = $this->vehicleService->readVehicle($queryParams);
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error("Error reading devices: {$e->getMessage()}");
            return new ErrorResponse(collect([
                Error::fromArray([
                    'status' => '500',
                    'title' => 'Internal Server Error',
                    'detail' => 'An error occurred while reading the devices.'
                ])
            ]));
        }
    }
}
