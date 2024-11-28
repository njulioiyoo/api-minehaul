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
     * Fetches tickets using updates retrieved from an external service.
     *
     * @param  int  $page  The current page for pagination.
     * @param  int  $limit  The number of records per page.
     * @param  string  $displayId  The display ID for filtering updates.
     * @param  int  $clean  Whether to clean the ticket data (default: 1).
     * @return array The list of ticket responses or an error message.
     */
    public function fetchTicketsUsingUpdates($page, $limit, $displayId, $clean = 1)
    {
        try {
            // Retrieve updates with associated device information
            $updates = $this->getUpdatesWithDevices($page, $limit, $displayId);

            // Check for errors in the updates response
            if (isset($updates['error']) && $updates['error']) {
                throw new \Exception($updates['message'] ?? 'Error fetching updates.');
            }

            $ticketResponses = []; // Initialize an array to store ticket responses

            // Iterate over each update
            foreach ($updates as $update) {
                $displayId = $update['display_id'] ?? null;
                $data = $update['data']['data'] ?? []; // Extract ticket data from the update

                // Skip if no ticket data is found
                if (empty($data)) {
                    Log::info("No data found for display_id {$displayId}. Skipping...");

                    continue;
                }

                // Process each ticket in the data array
                foreach ($data as $ticketId) {
                    if (! $ticketId) {
                        Log::info("Missing ticket_id for display_id {$displayId}. Skipping...");

                        continue;
                    }

                    // Process the individual ticket
                    $this->processTicket($ticketId, $displayId, $clean, $ticketResponses, $update);
                }
            }

            // Return all successfully processed ticket responses
            return $ticketResponses;
        } catch (\Exception $e) {
            // Return an error response in case of failure
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    /**
     * Processes an individual ticket by retrieving its data and storing it.
     *
     * @param  mixed  $ticketId  The ID of the ticket to process.
     * @param  string  $displayId  The display ID associated with the ticket.
     * @param  int  $clean  Whether to clean the ticket data.
     * @param  array  &$ticketResponses  A reference to the array storing ticket responses.
     * @param  array  $update  The original update data associated with the ticket.
     *
     * @throws RequestException Throws an exception if the ticket request fails.
     */
    private function processTicket($ticketId, $displayId, $clean, &$ticketResponses, $update)
    {
        try {
            // Send a GET request to fetch ticket details
            $response = $this->client->get("tickets/{$ticketId}?clean={$clean}", [
                'headers' => [
                    'accept' => 'application/json',
                    'X-Unit-Code' => $displayId,
                    'api-key' => config('minehaul.wls.load_scanner_key'),
                ],
            ]);

            // Decode the JSON response
            $responseData = json_decode($response->getBody()->getContents(), true);

            // Add the response to the ticketResponses array
            $ticketResponses[] = [
                'display_id' => $displayId,
                'ticket_id' => $ticketId,
                'data' => $responseData,
            ];

            // Store the ticket data in the Trip model
            $this->createTripFromTicket($ticketId, $displayId, $responseData, $update);
        } catch (RequestException $e) {
            // Handle 404 errors gracefully
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 404) {
                Log::info("Ticket not found for display_id {$displayId} and ticket_id {$ticketId}");
            } else {
                // Re-throw other exceptions
                throw $e;
            }
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
