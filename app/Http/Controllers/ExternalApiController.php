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
     * Synchronize tickets based on query parameters.
     *
     * @param  Request  $request  The incoming request with query parameters.
     * @return JsonResponse A JSON response containing the synced tickets.
     */
    public function syncTickets(Request $request): JsonResponse
    {
        try {
            // Retrieve query parameters from the request
            $queryParams = $request->query();

            // Extract parameters with default values
            $page = $queryParams['page'] ?? 1;
            $limit = $queryParams['limit'] ?? 5;
            $displayId = $queryParams['display_id'] ?? '';
            $clean = $queryParams['clean'] ?? 0;

            // Call the service to fetch tickets using updates
            $response = $this->apiService->fetchTicketsUsingUpdates($page, $limit, $displayId, $clean);

            // Return the response in JSON format
            return response()->json($response);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error fetching tickets using updates: '.$e->getMessage(), ['exception' => $e]);

            // Return a structured error response
            return $this->handleException($e, 'Error syncing tickets.');
        }
    }
}
