<?php

declare(strict_types=1);

namespace App\Services\System\User;

use App\Services\HttpService;

class UserService
{
    protected $httpService;

    public function __construct(HttpService $httpService)
    {
        $this->httpService = $httpService;
    }

    public function createUser($inputData, $headers, $queryParams)
    {
        $data = [
            'headers' => $headers,
            'json' => $inputData,
            'query' => $queryParams,
        ];

        return $this->httpService->handleRequest('post', route('v1.users.store'), $data);
    }

    public function readUser($queryParams, $headers)
    {
        $data = [
            'headers' => $headers,
            'query' => $queryParams,
        ];

        return $this->httpService->handleRequest('get', route('v1.users.index'), $data);
    }

    public function updateUser($userId, $inputData, $headers, $queryParams)
    {
        $data = [
            'headers' => $headers,
            'json' => $inputData,
            'query' => $queryParams,
        ];

        return $this->httpService->handleRequest('patch', route('v1.users.update', ['user' => $userId]), $data);
    }

    public function deleteUser($userId, $inputData, $headers, $queryParams)
    {
        $data = [
            'headers' => $headers,
            'json' => $inputData,
            'query' => $queryParams,
        ];

        return $this->httpService->handleRequest('delete', route('v1.users.destroy', ['user' => $userId]), $data);
    }
}
