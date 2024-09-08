<?php

namespace App\Http\Controllers\Api\V1\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\Vehicle\StoreVehicleRequest;
use App\Http\Requests\Configuration\Vehicle\UpdateVehicleRequest;
use App\Services\Configuration\Vehicle\VehicleService;
use Illuminate\Http\Request;
use App\Services\RequestHelperService;
use App\Traits\ExceptionHandlerTrait;

class VehicleController extends Controller
{
    use ExceptionHandlerTrait;

    protected $requestHelperService;
    protected $vehicleService;

    public function __construct(RequestHelperService $requestHelperService, VehicleService $vehicleService)
    {
        $this->requestHelperService = $requestHelperService;
        $this->vehicleService = $vehicleService;
    }

    public function createVehicle(StoreVehicleRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $vehicle = $this->vehicleService->createVehicle($validatedData);

            return response()->json($vehicle);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error creating vehicles');
        }
    }

    public function readVehicle(Request $request)
    {
        try {
            $queryParams = $request->query();
            $response = $this->vehicleService->readVehicle($queryParams);

            return response()->json($response);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error reading vehicles');
        }
    }

    public function showVehicle(Request $request)
    {
        try {
            [$input, $vehicleUid, $queryParams] = $this->requestHelperService->getInputAndId($request, 'vehicles', true);
            $response = $this->vehicleService->showVehicle($vehicleUid);

            return response()->json($response);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error show vehicles');
        }
    }

    public function updateVehicle(UpdateVehicleRequest $request)
    {
        try {
            $validatedData = $request->validated();
            [$input, $vehicleId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'vehicles', true);

            $vehicle = $this->vehicleService->updateVehicle($vehicleId, $validatedData);

            return response()->json($vehicle);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error updating vehicles');
        }
    }

    public function deleteVehicle(Request $request)
    {
        try {
            [$input, $vehicleId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'vehicles', true);
            $this->vehicleService->deleteVehicle($vehicleId);

            // Jika tidak ada data lain yang perlu dikembalikan maka kembalikan status 204 No Content
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error deleting vehicles');
        }
    }
}
