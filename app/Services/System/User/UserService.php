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
        $user = User::create($inputData);

        if (!$user) {
            throw new \Exception('Failed to create user');
        }

        return $user;
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

        $users = $query->paginate($perPage, ['*'], 'page[number]', $page);

        $data = $users->map(function ($user) {
            return $this->transformer->transform($user);
        })->values()->all(); // Convert to array

        return PaginationHelper::format($users, $data);
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
