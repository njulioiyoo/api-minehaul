<?php

declare(strict_types=1);

namespace App\Services\UserProfile;

use App\Models\User;
use App\Services\HttpService;
use LaravelJsonApi\Core\Responses\DataResponse;

class ProfileService
{
    protected $httpService;

    public function __construct(HttpService $httpService)
    {
        $this->httpService = $httpService;
    }

    public function readProfile($userId, $queryParams, $headers)
    {
        $user = User::findOrFail($userId);

        return new DataResponse($user);
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
