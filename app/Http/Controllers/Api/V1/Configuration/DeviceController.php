<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Configuration;

use App\Http\Controllers\Controller;
use App\Services\Configuration\Device\DeviceService;
use App\Services\HeaderService;
use App\Services\RequestHelperService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LaravelJsonApi\Core\Document\Error;
use LaravelJsonApi\Core\Responses\ErrorResponse;
use LaravelJsonApi\Core\Responses\DataResponse;
use Illuminate\Validation\ValidationException;

class DeviceController extends Controller
{
    protected $deviceService;
    protected $headerService;
    protected $requestHelperService;

    public function __construct(DeviceService $deviceService, HeaderService $headerService, RequestHelperService $requestHelperService)
    {
        $this->deviceService = $deviceService;
        $this->headerService = $headerService;
        $this->requestHelperService = $requestHelperService;
    }

    public function createDevice(Request $request)
    {
        // Extract input and queryParams
        $input = $request->input(); // This should now be the full request payload

        try {
            // Validate the input data
            $device = $this->deviceService->createDevice($input);

            // Return a DataResponse with the created device
            return new DataResponse($device);
        } catch (ValidationException $e) {
            // Log the validation error
            Log::error("ValidationException: {$e->getMessage()}");

            // Return JSON:API Error Response with validation errors
            return new ErrorResponse(collect($e->errors())->map(function ($error) {
                return Error::fromArray([
                    'status' => '422',
                    'title' => 'Validation Error',
                    'detail' => $error[0],
                ]);
            }));
        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error("Unexpected Exception: {$e->getMessage()}");

            // Return a generic error response
            return new ErrorResponse(collect([
                Error::fromArray([
                    'status' => '500',
                    'title' => 'Internal Server Error',
                    'detail' => 'An unexpected error occurred while creating the device.'
                ])
            ]));
        }
    }

    public function readDevice(Request $request)
    {
        $queryParams = $request->query();

        try {
            $response = $this->deviceService->readDevice($queryParams);
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

    public function updateDevice(Request $request)
    {
        [$input, $deviceUid, $queryParams] = $this->requestHelperService->getInputAndId($request, 'devices', true);

        try {
            $device = $this->deviceService->updateDevice($deviceUid, $input);
            return new DataResponse($device);
        } catch (ValidationException $e) {
            // Log the validation error
            Log::error("ValidationException: {$e->getMessage()}");

            // Return JSON:API Error Response with validation errors
            return new ErrorResponse(collect($e->errors())->map(function ($error) {
                return Error::fromArray([
                    'status' => '422',
                    'title' => 'Validation Error',
                    'detail' => $error[0],
                ]);
            }));
        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error("Unexpected Exception: {$e->getMessage()}");

            // Return a generic error response
            return new ErrorResponse(collect([
                Error::fromArray([
                    'status' => '500',
                    'title' => 'Internal Server Error',
                    'detail' => 'An unexpected error occurred while creating the device.'
                ])
            ]));
        }
    }

    public function deleteDevice(Request $request)
    {
        [$input, $deviceUid, $queryParams] = $this->requestHelperService->getInputAndId($request, 'devices', true);

        try {
            $this->deviceService->deleteDevice($deviceUid);
            return response()->json(['message' => 'Device deleted successfully.']);
        } catch (\Exception $e) {
            Log::error("Error deleting device: {$e->getMessage()}");
            return new ErrorResponse(collect([
                Error::fromArray([
                    'status' => '500',
                    'title' => 'Internal Server Error',
                    'detail' => 'An error occurred while deleting the device.'
                ])
            ]));
        }
    }
}
