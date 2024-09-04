<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\System\User\StoreUserRequest;
use App\Http\Requests\System\User\UpdateUserRequest;
use App\Services\RequestHelperService;
use App\Services\System\User\UserService;
use App\Traits\ExceptionHandlerTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ExceptionHandlerTrait;

    protected $userService;

    protected $requestHelperService;

    public function __construct(UserService $userService, RequestHelperService $requestHelperService)
    {
        $this->userService = $userService;
        $this->requestHelperService = $requestHelperService;
    }

    public function createUser(StoreUserRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $roles = $validatedData['roles'];
            $user = $this->userService->createUser($validatedData, $roles);

            return response()->json($user);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error creating user');
        }
    }

    public function readUser(Request $request)
    {
        try {
            $queryParams = $request->query();
            $response = $this->userService->readUser($queryParams);

            return response()->json($response);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error reading user');
        }
    }

    public function updateUser(UpdateUserRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $roles = $validatedData['roles'] ?? [];
            [$input, $userId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'users', true);
            $user = $this->userService->updateUser($userId, $validatedData, $roles);

            return response()->json($user);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error updating user');
        }
    }

    public function deleteUser(Request $request)
    {
        try {
            [$input, $userId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'users', true);
            $this->userService->deleteUser($userId);

            return response()->json(['message' => 'User deleted successfully.']);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error deleting user');
        }
    }
}
