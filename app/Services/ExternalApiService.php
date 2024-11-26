<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ExternalApiService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('minehaul.wls.load_scanner_url'),
            'timeout' => 10.0, // Timeout dalam detik
        ]);
    }

    public function getUpdates($page = 1, $limit = 10)
    {
        try {
            // Make a GET request to the 'get_updates' endpoint of the external API
            $response = $this->client->get('get_updates', [
                // Set the required headers for the API request
                'headers' => [
                    'accept' => 'application/json', // Specify the response format as JSON
                    'X-Unit-Code' => 'LS102', // Custom header to identify the unit code
                    'api-key' => config('minehaul.wls.load_scanner_key'), // API key from configuration for authentication
                ],
                // Include query parameters for pagination (page and limit)
                'query' => [
                    'page' => $page, // Page number for the data
                    'limit' => $limit, // Number of items per page
                ],
            ]);

            // Decode the JSON response body into an associative array and return it
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            // Handle any exceptions that occur during the API request
            // Return an error message with the exception details
            return [
                'error' => true, // Indicate that an error occurred
                'message' => $e->getMessage(), // Provide the error message
            ];
        }
    }
}
