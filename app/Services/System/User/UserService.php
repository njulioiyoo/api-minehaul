<?php

declare(strict_types=1);

namespace App\Services\System\User;

use App\Helpers\PaginationHelper;
use App\Models\People;
use App\Models\RoleHasPit;
use App\Models\User;
use App\Traits\ExceptionHandlerTrait;
use App\Transformers\UserTransformer;
use Illuminate\Support\Arr;
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

    /**
     * Creates a new user in the system.
     *
     * @param  array  $inputData  The input data for creating the user.
     * @param  array  $roles  The roles to be assigned to the user (optional).
     * @return mixed The JSON API response with the transformed user data.
     *
     * @throws \Illuminate\Database\DatabaseException If the database transaction fails.
     */
    public function createUser(array $inputData, array $roles = [])
    {
        return DB::transaction(function () use ($inputData, $roles) {
            // Create a new record in the People table
            $people = People::create([
                'full_name' => $inputData['full_name'],
                'account_id' => $inputData['account_id'],
            ]);

            // Add the created People ID to input data for User creation
            $inputData['people_id'] = $people->id;

            // Remove unnecessary fields (full_name, account_id) before creating a User
            $userData = Arr::except($inputData, ['full_name', 'account_id']);

            // Create a new User record
            $user = $this->userModel->create($userData);

            // Clear cache for this user
            Cache::forget('user_'.$user->id);

            // If roles are provided, synchronize roles and permissions
            if (! empty($roles)) {
                $this->updateRolesAndPermissions($user, $roles);
            }

            // Return the JSON API response with transformed user data
            return $this->formatJsonApiResponse(
                $this->transformer->transform($user)
            );
        });
    }

    /**
     * Updates the roles and permissions of a user.
     *
     * @param  \App\Models\User  $user  The user object.
     * @param  array  $roles  An array of role IDs.
     * @return void
     */
    private function updateRolesAndPermissions($user, $roles)
    {
        // Synchronize the user's roles
        $user->syncRoles($roles);

        // Fetch and synchronize permissions based on the assigned roles
        $permissions = Role::whereIn('id', $roles)
            ->with('permissions:id,name')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->toArray();

        // Assign the unique permissions to the user
        $user->syncPermissions($permissions);
    }

    /**
     * Retrieves a list of users based on the provided query parameters.
     *
     * @param  array  $queryParams  An array containing pagination and filter parameters.
     * @return array Paginated and formatted user data.
     */
    public function readUser(array $queryParams)
    {
        // Set pagination parameters (default: 10 per page, starting at page 1)
        $perPage = $queryParams['page']['size'] ?? 10;
        $page = $queryParams['page']['number'] ?? 1;

        // Start building the query for the User model
        $query = User::query();

        // Apply filters if any are provided in query parameters
        if (isset($queryParams['filter'])) {
            foreach ($queryParams['filter'] as $field => $value) {
                $query->where($field, $value);
            }
        }

        // Paginate the query results
        $users = $query->paginate($perPage, ['*'], 'page[number]', $page);

        // Transform the users for API response
        $data = $users->map(function ($user) {
            return $this->transformer->transform($user);
        })->values()->all(); // Convert to array

        // Format and return the paginated response
        return PaginationHelper::format($users, $data);
    }

    /**
     * Updates a user with the provided user ID and input data.
     *
     * @param  string  $userId  The ID of the user to update.
     * @param  array  $inputData  The input data to update the user with.
     * @param  array  $roles  The roles to update for the user (optional).
     * @return The updated user as a JSON API response.
     */
    public function updateUser(string $userId, array $inputData, array $roles = [])
    {
        return DB::transaction(function () use ($userId, $inputData, $roles) {
            // Retrieve the user by user ID
            $user = $this->userModel->where('uid', $userId)->first();

            // Update related People data if available
            if (isset($user->people)) {
                $user->people->update([
                    'full_name' => $inputData['full_name'],
                    'account_id' => $inputData['account_id'],
                ]);
            }

            // Remove unnecessary fields from input data
            $userData = Arr::except($inputData, ['full_name', 'account_id']);

            // Update user data
            $user->update($userData);

            // Update the cache for this user
            Cache::put("user_$userId", $user, 60);

            // If roles are provided, update roles and permissions
            if (! empty($roles)) {
                $this->updateRolesAndPermissions($user, $roles);

                // Synchronize RoleHasPit for the roles
                $this->syncRolePits($user, $roles);
            }

            // Return the updated user as a JSON API response
            return $this->formatJsonApiResponse(
                $this->transformer->transform($user)
            );
        });
    }

    /**
     * Deletes a user by the provided user ID.
     *
     * @param  int  $userId  The ID of the user to be deleted
     * @return void
     *
     * @throws \Exception If an error occurs during the deletion process
     */
    public function deleteUser($userId)
    {
        try {
            // Delete the user by user ID
            $this->userModel->where('uid', $userId)->delete();

            // Clear the cache for this user
            Cache::forget("user_$userId");
        } catch (\Exception $e) {
            // Log the error if an exception occurs during deletion
            Log::error("Error deleting user with ID: {$userId}, Error: {$e->getMessage()}");
            throw $e; // Rethrow the exception for higher-level handling
        }
    }

    /**
     * Retrieves a user by their user ID and returns the user data as a JSON API response.
     *
     * @param  string  $userId  The unique identifier of the user to retrieve.
     * @return mixed The JSON API response with the transformed user data.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the user is not found in the database.
     */
    public function showUser(string $userId)
    {
        // Retrieve the user from the cache or the database if not cached
        $user = Cache::remember("device_$userId", 60, function () use ($userId) {
            return $this->userModel->where('uid', $userId)->firstOrFail();
        });

        // Return the user data as a JSON API response using the transformer
        return $this->formatJsonApiResponse(
            $this->transformer->transform($user)
        );
    }

    /**
     * Synchronizes RoleHasPit data for a user based on roles using Query Builder.
     *
     * @param  \App\Models\User  $user
     * @param  array  $roles  An array of role IDs.
     * @return void
     */
    private function syncRolePits($user, array $roles)
    {
        // Ensure account_id is always treated as an array
        $accountIds = (array) $user->people->account_id;

        // Retrieve unique pit IDs associated with the account IDs
        $pitIds = DB::table('pits')
            ->whereIn('account_id', $accountIds)
            ->pluck('id')
            ->unique();

        if ($pitIds->isEmpty()) {
            // If no pits are found, delete existing data and return early
            DB::table('role_has_pits')->where('account_id', $user->people->account_id)->delete();

            return;
        }

        // Build the data for RoleHasPit using a collection
        $roleHasPitData = collect($roles)
            ->crossJoin($pitIds) // Create all combinations of roles and pit IDs
            ->map(fn ($pair) => [
                'role_id' => $pair[0],
                'pit_id' => $pair[1],
                'account_id' => $user->people->account_id,
                'created_at' => now(),
                'updated_at' => now(),
            ])->all();

        // Perform the synchronization in a database transaction
        DB::transaction(function () use ($roleHasPitData, $user) {
            // Delete existing RoleHasPit entries for the user's account
            DB::table('role_has_pits')
                ->where('account_id', $user->people->account_id)
                ->delete();

            // Insert new RoleHasPit entries if data is available
            if (! empty($roleHasPitData)) {
                DB::table('role_has_pits')->insert($roleHasPitData);
            }
        });
    }
}
