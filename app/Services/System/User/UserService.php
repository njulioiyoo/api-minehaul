<?php

declare(strict_types=1);

namespace App\Services\System\User;

use App\Helpers\PaginationHelper;
use App\Models\User;
use App\Traits\ExceptionHandlerTrait;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UserService
{
    use ExceptionHandlerTrait;

    protected $transformer;

    protected $userModel;

    public function __construct(UserTransformer $transformer, User $user)
    {
        $this->transformer = $transformer;
        $this->userModel = $user;
    }

    public function createUser(array $inputData, array $roles = [])
    {
        return DB::transaction(function () use ($inputData, $roles) {
            $user = $this->userModel->create($inputData);

            // Clear cache related to users
            Cache::forget('user_'.$user->id);

            if (! empty($roles)) {
                $user->syncRoles($roles);

                $permissions = Role::whereIn('id', $roles)
                    ->with('permissions:id,name')
                    ->get()
                    ->pluck('permissions')
                    ->flatten()
                    ->pluck('name')
                    ->unique()
                    ->toArray();

                $user->syncPermissions($permissions);
            }

            return $this->formatJsonApiResponse(
                $this->transformer->transform($user)
            );
        });
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

    public function updateUser(string $userId, array $inputData, array $roles = [])
    {
        return DB::transaction(function () use ($userId, $inputData, $roles) {
            $user = $this->userModel->findOrFail($userId);

            $user->update($inputData);

            // Update cache
            Cache::put("user_$userId", $user, 60);

            if (! empty($roles)) {
                $user->syncRoles($roles);

                $permissions = Role::whereIn('id', $roles)
                    ->with('permissions:id,name')
                    ->get()
                    ->pluck('permissions')
                    ->flatten()
                    ->pluck('name')
                    ->unique()
                    ->toArray();

                $user->syncPermissions($permissions);
            }

            // Menggunakan transformer untuk format response JSON API
            return $this->formatJsonApiResponse(
                $this->transformer->transform($user)
            );
        });
    }

    public function deleteUser($userId)
    {
        try {
            $user = $this->userModel->findOrFail($userId);
            $user->delete();

            // Clear cache
            Cache::forget("user_$userId");
        } catch (\Exception $e) {
            Log::error("Error deleting user with ID: {$userId}, Error: {$e->getMessage()}");
            throw $e;
        }
    }

    public function showUser(string $userId)
    {
        $user = Cache::remember("device_$userId", 60, function () use ($userId) {
            return $this->userModel->where('id', $userId)->firstOrFail();
        });

        // Menggunakan transformer untuk format response JSON API
        return $this->formatJsonApiResponse(
            $this->transformer->transform($user)
        );
    }
}
