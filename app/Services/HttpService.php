<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use LaravelJsonApi\Core\Document\Error;
use LaravelJsonApi\Core\Responses\ErrorResponse;

class HttpService
{
    protected $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function handleRequest($method, $url, $data)
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
            dd($e);
            $responseBody = json_decode($e->getResponse()->getBody()->getContents(), true);
            $errors = $responseBody['errors'] ?? [];

            // Convert the errors into JSON:API Error objects
            $errors = collect($errors)->map(function ($error) {
                return Error::fromArray($error);
            });

            // Return JSON:API Error Response
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
