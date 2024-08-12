<?php

declare(strict_types=1);

namespace App\Services\UserProfile;

use App\Services\HttpService;

class ProfileService
{
    protected $httpService;

    public function __construct(HttpService $httpService)
    {
        $this->httpService = $httpService;
    }

    public function readProfile($userId, $queryParams, $headers)
    {
        $data = [
            'headers' => $headers,
            'query' => $queryParams,
        ];

        return $this->httpService->handleRequest('get', route('v1.users.show', ['user' => $userId]), $data);
    }

    public function updateProfile($userId, $inputData, $headers, $queryParams)
    {
        $data = [
            'headers' => $headers,
            'json' => $inputData,
            'query' => $queryParams,
        ];

        return $this->httpService->handleRequest('patch', route('v1.users.update', ['user' => $userId]), $data);
    }
}
