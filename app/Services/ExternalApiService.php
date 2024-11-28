<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\PaginationHelper;
use App\Models\Device;
use App\Models\Trip;
use App\Models\TripLoadScanner;
use App\Transformers\TripTransformer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class ExternalApiService
{
    protected $client;

    protected Trip $tripModel;

    protected TripTransformer $transformer;

    public function __construct(Trip $trip, TripTransformer $transformer)
    {
        $this->client = new Client([
            'base_uri' => config('minehaul.wls.load_scanner_url'),
            'timeout' => 10.0, // Timeout in seconds
        ]);

        $this->tripModel = $trip;
        $this->transformer = $transformer;
    }

    /**
     * Fetches updates along with associated devices for the authenticated user's account.
     *
     * @param  int  $page  The page number for pagination (default is 1).
     * @param  int  $limit  The number of results per page (default is 10).
     * @return array An array of valid responses containing device and update data,
     *               or an error array if exceptions occur.
     *
     * @throws \Exception If the account or devices are not found for the authenticated user.
     * @throws RequestException If an error occurs during the API request for updates.
     */
    public function getUpdatesWithDevices($page = 1, $limit = 5, $displayId = '')
    {
        try {
            // Retrieve the authenticated user
            $user = auth()->user();

            // Get the account associated with the user
            $account = $user?->people?->account;

            if (! $account) {
                throw new \Exception('Account not found for the authenticated user.');
            }

            // Fetch all devices associated with the account
            $devices = Device::where([
                'account_id' => $account->id,
                'device_type_id' => 3,
            ])
                ->orWhere('name', 'like', '%'.$displayId.'%')->get();

            if ($devices->isEmpty()) {
                throw new \Exception('No devices found for the given account.');
            }

            // Remove duplicate devices based on display_id
            $uniqueDevices = $devices->unique('display_id');

            $allResponses = []; // To store valid responses for each device

            // Iterate over each unique device to make individual requests
            foreach ($uniqueDevices as $device) {
                $unitCode = $device->display_id;

                try {
                    // Make the GET request for the current device
                    $response = $this->client->get('tickets/get_updates', [
                        'headers' => [
                            'accept' => 'application/json',
                            'X-Unit-Code' => $unitCode, // Dynamic X-Unit-Code for each device
                            'api-key' => config('minehaul.wls.load_scanner_key'),
                        ],
                        'query' => [
                            'page' => $page,
                            'limit' => $limit,
                            'display_id' => $displayId,
                        ],
                    ]);

                    // Decode the JSON response
                    $responseData = json_decode($response->getBody()->getContents(), true);

                    // Store the valid response along with device details
                    $allResponses[] = [
                        'device_id' => $device->id,
                        'display_id' => $unitCode,
                        'data' => $responseData,
                    ];
                } catch (RequestException $e) {
                    // Check if the response has a 404 status code
                    if ($e->getResponse() && $e->getResponse()->getStatusCode() === 404) {
                        Log::info("Device skipped: Unit not found for display_id {$unitCode}");

                        continue; // Skip processing this device
                    }

                    // Log other exceptions for better debugging
                    Log::error("Error fetching updates for device {$unitCode}: ".$e->getMessage());

                    // Rethrow the exception to be handled in the controller
                    throw $e;
                }
            }

            // Return all collected valid responses
            return $allResponses;
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
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
    public function fetchTicketsUsingUpdates($page, $limit, $displayId, $clean = 1)
    {
        try {
            // Fetch updates and associated device data
            $updates = $this->getUpdatesWithDevices($page, $limit, $displayId);

            // Check if updates contain errors
            if (isset($updates['error']) && $updates['error']) {
                throw new \Exception($updates['message'] ?? 'Error fetching updates.');
            }

            $ticketResponses = []; // To store ticket responses for each device

            // Iterate over each update
            foreach ($updates as $update) {
                $displayId = $update['display_id'] ?? null;
                $data = $update['data']['data'] ?? []; // Assuming 'data' contains an array of items

                // Check if 'data' is not empty
                if (empty($data)) {
                    Log::info("No data found in update for display_id {$displayId}. Skipping...");

                    continue;
                }

                // Loop through the 'data' array to get ticket_id
                foreach ($data as $ticketData) {
                    $ticketId = $ticketData ?? null; // Assuming each item in 'data' has a 'ticket_id'

                    if (! $ticketId) {
                        Log::info("Missing ticket_id in data for display_id {$displayId}. Skipping...");

                        continue;
                    }

                    try {
                        // Make the GET request for the ticket
                        $response = $this->client->get("tickets/{$ticketId}?clean=$clean", [
                            'headers' => [
                                'accept' => 'application/json',
                                'X-Unit-Code' => $displayId,
                                'api-key' => config('minehaul.wls.load_scanner_key'),
                            ],
                        ]);

                        // Decode the JSON response
                        $responseData = json_decode($response->getBody()->getContents(), true);

                        // Store the valid response along with ticket details
                        $ticketResponses[] = [
                            'display_id' => $displayId,
                            'ticket_id' => $ticketId,
                            'data' => $responseData,
                        ];

                        // Insert data into the Trip model after successful ticket retrieval
                        $this->createTripFromTicket($ticketId, $displayId, $responseData, $update);
                    } catch (RequestException $e) {
                        // Check for 404 status code
                        if ($e->getResponse() && $e->getResponse()->getStatusCode() === 404) {
                            Log::info("Ticket not found for display_id {$displayId} and ticket_id {$ticketId}");

                            continue; // Skip processing this ticket
                        }

                        // Rethrow other exceptions
                        throw $e;
                    }
                }
            }

            // Return all collected ticket responses
            return $ticketResponses;
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Creates a new trip in the database based on the provided ticket ID, display ID, and response data.
     *
     * This method attempts to create a trip record using the given information.
     * It logs a success message upon successful creation and logs an error message
     * if an exception occurs during the trip creation process.
     *
     * @param  string  $ticketId  The unique identifier of the ticket.
     * @param  string  $displayId  The display identifier associated with the device.
     * @param  array  $responseData  The response data containing trip details.
     */
    private function createTripFromTicket(string $ticketId, string $displayId, array $responseData, array $getUpdatesWithDevices)
    {
        // Get the nested 'data' array from the response
        $data = $responseData['data'];

        try {
            // Call the model's method to create the trip using the data
            TripLoadScanner::updateOrCreateFromApiData($data, $getUpdatesWithDevices);

            Log::info("Successfully created trip for ticket_id {$ticketId} and display_id {$displayId}");
        } catch (\Exception $e) {
            Log::error("Error creating trip for ticket_id {$ticketId} and display_id {$displayId}: ".$e->getMessage());
        }
    }

    public function fetchTripLoadScanners(array $queryParams)
    {
        $perPage = $queryParams['page']['size'] ?? 10;
        $page = $queryParams['page']['number'] ?? 1;

        $query = $this->tripModel->with(['driver', 'tripType']);

        // Apply filters if any
        if (isset($queryParams['filter'])) {
            foreach ($queryParams['filter'] as $field => $value) {
                $query->where($field, $value);
            }
        }

        // Get paginated trip data
        $trips = $query->paginate($perPage, ['*'], 'page[number]', $page);

        // Transform device data using the transformer
        $data = $trips->map(fn ($trip) => $this->transformer->transform($trip))->values()->all();

        // Return paginated data with formatting
        return PaginationHelper::format($trips, $data);
    }
}
