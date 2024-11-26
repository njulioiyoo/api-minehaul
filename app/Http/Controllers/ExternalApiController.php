<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ExternalApiService;
use App\Traits\ExceptionHandlerTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExternalApiController extends Controller
{
    use ExceptionHandlerTrait;

    protected $apiService;

    public function __construct(ExternalApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Calls the external API and returns the response from the API.
     *
     * The page and limit query parameters are used to fetch the required number of updates.
     * If the parameters are not provided, the default values of 1 (page) and 10 (limit) are used.
     */
    public function getUpdates(Request $request): JsonResponse
    {
        try {
            // Get query parameters from the request
            $queryParams = $request->query();

            // Call the external API through the service
            $response = $this->apiService->getUpdates($queryParams['page'] ?? 1, $queryParams['limit'] ?? 10);

            // Return the response from the external API
            return response()->json($response);
        } catch (\Exception $e) {
            // Log the error if an exception occurs
            Log::error($e->getMessage(), ['exception' => $e]);

            // Handle the exception and return an error response
            return $this->handleException($e, 'Error fetching updates');
        }
    }
}
