<?php

declare(strict_types=1);

namespace App\Services\System\User;

use App\Models\User;
use App\Services\HttpService;
use App\Helpers\PaginationHelper;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Log;

class UserService
{
    protected $httpService;
    protected $transformer;

    public function __construct(HttpService $httpService, UserTransformer $transformer)
    {
        $this->httpService = $httpService;
        $this->transformer = $transformer;
    }

    public function createUser(array $inputData)
    {
        $device = User::create($inputData);

        if (!$device) {
            throw new \Exception('Failed to create user');
        }

        return $device;
    }

    public function readUser(array $queryParams)
    {
        $perPage = $queryParams['page']['size'] ?? 10;
        $page = $queryParams['page']['number'] ?? 1;

        $query = User::query();

        if (isset($queryParams['filter'])) {
            foreach ($queryParams['filter'] as $field => $value) {
                $query->where($field, $value);
            }
        }

        $devices = $query->paginate($perPage, ['*'], 'page[number]', $page);

        $data = $devices->map(function ($device) {
            return $this->transformer->transform($device);
        })->values()->all(); // Convert to array

        return PaginationHelper::format($devices, $data);
    }

    public function updateUser(string $userId, array $inputData)
    {
        $user = User::find($userId);

        if (!$user) {
            throw new \Exception('User not found');
        }

        $user->update($inputData);

        return $user;
    }

    public function deleteUser($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            Log::info('User not found with ID: ' . $userId);
            throw new \Exception('User not found');
        }

        $user->delete();
    }
}
