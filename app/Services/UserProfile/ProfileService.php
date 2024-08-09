<?php

declare(strict_types=1);

namespace App\Services\UserProfile;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;
use LaravelJsonApi\Core\Document\Error;
use LaravelJsonApi\Core\Responses\ErrorResponse;

class ProfileService
{
    protected $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function readProfile($userId, $queryParams, $headers)
    {
        $data = [
            'headers' => $headers,
            'query' => $queryParams,
        ];

        return $this->handleRequest('get', route('v1.users.show', ['user' => $userId]), $data);
    }

    public function updateProfile($userId, $inputData, $headers, $queryParams)
    {
        $data = [
            'headers' => $headers,
            'json' => $inputData,
            'query' => $queryParams,
        ];

        return $this->handleRequest('patch', route('v1.users.update', ['user' => $userId]), $data);
    }

    private function handleRequest($method, $url, $data)
    {
        try {
            $response = $this->httpClient->$method($url, $data);
            $responseBody = json_decode((string) $response->getBody(), true);
            $responseStatus = $response->getStatusCode();
            $responseHeaders = $this->parseHeaders($response->getHeaders());

            unset($responseHeaders['Transfer-Encoding']);

            return response()->json($responseBody, $responseStatus)
                ->withHeaders($responseHeaders);
        } catch (ClientException $e) {
            Log::error('ClientException', [
                'message' => $e->getMessage(),
                'response' => $e->getResponse()->getBody()->getContents(),
            ]);

            $errors = json_decode($e->getResponse()->getBody()->getContents(), true)['errors'];
            $errors = collect($errors)->map(function ($error) {
                return Error::fromArray($error);
            });

            return ErrorResponse::make($errors);
        }
    }

    private function parseHeaders($headers)
    {
        return collect($headers)->map(function ($item) {
            return $item[0];
        })->toArray();
    }
}
