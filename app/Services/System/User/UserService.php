<?php

declare(strict_types=1);

namespace App\Services\System\User;

use App\Models\User;
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

    public function readUser(array $queryParams)
    {
        // Define default pagination parameters
        $perPage = $queryParams['page']['size'] ?? 15;
        $page = $queryParams['page']['number'] ?? 1;

        // Fetch devices with relations
        $query = User::with('userRoles');

        // Apply filters if needed
        if (isset($queryParams['filter'])) {
            foreach ($queryParams['filter'] as $field => $value) {
                $query->where($field, $value);
            }
        }

        // Apply pagination
        $user = $query->paginate($perPage, ['*'], 'page[number]', $page);

        // Transform data to include relations
        $data = $user->map(function ($user) {
            return [
                'type' => 'users',
                'id' => $user->id,
                'attributes' => [
                    'id' => $user->id,
                    // 'roles' => $user?->roles ? [
                    //     'id' => $user?->roles->id,
                    //     'name' => $user?->roles->name,
                    // ] : null,
                    // 'pit' => $user->pit ? [
                    //     'id' => $user->pit->id,
                    //     'name' => $user->pit->name,
                    //     'description' => $user->pit->description,
                    // ] : null,
                    // 'device_type' => $user->deviceType ? [
                    //     'id' => $user->deviceType->id,
                    //     'name' => $user->deviceType->name,
                    // ] : null,
                    // 'device_make' => $user->deviceMake ? [
                    //     'id' => $user->deviceMake->id,
                    //     'name' => $user->deviceMake->name,
                    // ] : null,
                    // 'device_model' => $user->deviceModel ? [
                    //     'id' => $user->deviceModel->id,
                    //     'name' => $user->deviceModel->name,
                    // ] : null,
                    'username' => $user->username,
                    'person_id' => $user->person_id,
                    'email' => $user->email,
                ],
                'links' => [
                    'self' => url("/api/v1/users/{$user->uid}"),
                ],
            ];
        });

        return [
            'meta' => [
                'page' => [
                    'currentPage' => $user->currentPage(),
                    'from' => $user->firstItem(),
                    'lastPage' => $user->lastPage(),
                    'perPage' => $user->perPage(),
                    'to' => $user->lastItem(),
                    'total' => $user->total(),
                ]
            ],
            'jsonapi' => [
                'version' => '1.0'
            ],
            'links' => [
                'first' => $user->url(1),
                'last' => $user->url($user->lastPage()),
                'next' => $user->nextPageUrl(),
                'prev' => $user->previousPageUrl(),
            ],
            'data' => $data->values()->all() // Convert to array
        ];
    }

    public function readUserV2($queryParams, $headers)
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
