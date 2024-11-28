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
     * Synchronizes tickets by fetching updates from an API service.
     *
     * @param  Request  $request  The HTTP request containing query parameters.
     * @return JsonResponse The JSON response with fetched ticket data or error details.
     */
    public function syncTickets(Request $request): JsonResponse
    {
        try {
            // Retrieve query parameters and set defaults where necessary
            $queryParams = $request->query() + [
                'page' => 1,
                'limit' => 5,
                'display_id' => '',
                'clean' => 0,
            ];

            // Pass the parameters to the service
            $response = $this->apiService->fetchTicketsUsingUpdates(
                $queryParams['page'],
                $queryParams['limit'],
                $queryParams['display_id'],
                $queryParams['clean']
            );

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Error syncing tickets: '.$e->getMessage(), ['exception' => $e]);

            return $this->handleException($e, 'Error syncing tickets.');
        }
    }

    /**
     * Fetch trip load scanners based on query parameters.
     *
     * @param  Request  $request  The incoming request with query parameters.
     * @return JsonResponse A JSON response containing the trip load scanners.
     */
    public function tripLoadScanners(Request $request): JsonResponse
    {
        try {
            // Retrieve query parameters from the request
            $queryParams = $request->query();

            // Call the service to fetch trip load scanners
            $response = $this->apiService->fetchTripLoadScanners($queryParams);

            // Return the response in JSON format
            return response()->json($response);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error fetching trip load scanners: '.$e->getMessage(), ['exception' => $e]);

            // Return a structured error response
            return $this->handleException($e, 'Error fetching trip load scanners.');
        }
    }
}
