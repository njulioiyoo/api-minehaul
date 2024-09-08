<?php

namespace App\Http\Controllers;

use App\Transformers\ReferenceModuleTransformer;
use Illuminate\Http\Request;
use App\Services\RequestHelperService;
use App\Traits\ExceptionHandlerTrait;

class ReferenceModuleController extends Controller
{
    use ExceptionHandlerTrait;

    protected $transformer;
    protected $requestHelperService;

    public function __construct(ReferenceModuleTransformer $transformer, RequestHelperService $requestHelperService)
    {
        $this->transformer = $transformer;
        $this->requestHelperService = $requestHelperService;
    }

    /**
     * Handle data based on type from the request body.
     *
     * @param Request $request
     * @param string $typeKey
     * @param string $transformMethod
     * @param string $errorMessage
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleData(Request $request, string $typeKey, string $transformMethod, string $errorMessage)
    {
        try {
            $type = $request->input("data.{$typeKey}", null);

            // Dapatkan data lengkap dari transform method
            $allData = $this->transformer->{$transformMethod}();

            // Dapatkan semua kunci yang valid dari data
            $validTypes = array_keys($allData);

            // Cek apakah tipe yang diberikan valid
            if ($type && !in_array($type, $validTypes)) {
                return $this->jsonApiErrorResponse(
                    400,
                    'Invalid Type',
                    "The provided type '{$type}' is not valid."
                );
            }

            // Ambil data berdasarkan tipe, atau semua jika tipe tidak diberikan
            $attributes = $type ? [$type => $allData[$type]] : $allData;

            return response()->json([
                'jsonapi' => [
                    'version' => '1.0',
                ],
                'data' => [
                    [
                        'type' => $type ?? 'all',
                        'attributes' => $attributes,
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return $this->handleException($e, $errorMessage);
        }
    }

    /**
     * Handle device data based on type from the request body.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDeviceData(Request $request)
    {
        return $this->handleData($request, 'type', 'transformDevice', 'Error reading device reference');
    }

    /**
     * Handle vehicle data based on type from the request body.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVehicleData(Request $request)
    {
        return $this->handleData($request, 'type', 'transformVehicle', 'Error reading vehicle reference');
    }

    /**
     * Standardized JSON:API error response
     *
     * @param int $statusCode
     * @param string $title
     * @param string $detail
     * @return \Illuminate\Http\JsonResponse
     */
    private function jsonApiErrorResponse(int $statusCode, string $title, string $detail)
    {
        return response()->json([
            'errors' => [
                [
                    'status' => (string)$statusCode,
                    'title' => $title,
                    'detail' => $detail,
                ]
            ]
        ], $statusCode);
    }
}
