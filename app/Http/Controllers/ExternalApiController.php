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
     * Fetches tickets using updates by calling the ExternalApiService.
     *
     * The method takes three query parameters: page, limit, and clean. The page
     * parameter is used to specify the page number of the results to return. The
     * limit parameter is used to specify the number of results to return per
     * page. The clean parameter is used to specify whether the existing tickets
     * should be cleaned before fetching new ones.
     *
     * If the request is successful, the method returns a JSON response with a
     * structure like the following:
     *
     * [
     *     'success' => true,
     *     'data' => [
     *         [
     *             'display_id' => string,
     *             'ticket_id' => string,
     *             'data' => array,
     *         ],
     *         // ...
     *     ],
     * ]
     *
     * If an error occurs during the request, the method logs the error and
     * returns a structured error response. The error response will have a
     * structure like the following:
     *
     * [
     *     'success' => false,
     *     'error' => [
     *         'code' => integer,
     *         'message' => string,
     *     ],
     * ]
     */
    public function syncTickets(Request $request): JsonResponse
    {
        try {
            // Retrieve the query parameters from the request
            $page = $request->query('page', 1);
            $limit = $request->query('limit', 5);
            $displayId = $request->query('display_id', '');
            $clean = $request->query('clean', 0);

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
