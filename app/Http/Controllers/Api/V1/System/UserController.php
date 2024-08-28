<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\System\User\StoreUserRequest;
use App\Http\Requests\System\User\UpdateUserRequest;
use App\Services\RequestHelperService;
use App\Services\System\User\UserService;
use Illuminate\Http\Request;
use LaravelJsonApi\Core\Responses\ErrorResponse;
use Illuminate\Support\Facades\Log;
use LaravelJsonApi\Core\Document\Error;
use LaravelJsonApi\Core\Responses\DataResponse;

class UserController extends Controller
{
    protected $userService;

    protected $requestHelperService;

    public function __construct(UserService $userService, RequestHelperService $requestHelperService)
    {
        $this->userService = $userService;
        $this->requestHelperService = $requestHelperService;
    }

    public function createUser(StoreUserRequest $request)
    {
        $validatedData = $request->validated();
        $user = $this->userService->createUser($validatedData);

        return new DataResponse($user);
    }

    public function readUser(Request $request)
    {
        $queryParams = $request->query();

        try {
            $response = $this->userService->readUser($queryParams);
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error("Error reading user: {$e->getMessage()}");
            return new ErrorResponse(collect([
                Error::fromArray([
                    'status' => '500',
                    'title' => 'Internal Server Error',
                    'detail' => 'An error occurred while reading the user.'
                ])
            ]));
        }
    }

    public function updateUser(UpdateUserRequest $request)
    {
        $validatedData = $request->validated();
        [$input, $userId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'users', true);
        $user = $this->userService->updateUser($userId, $validatedData);

        return new DataResponse($user);
    }

    public function deleteUser(Request $request)
    {
        [$input, $userId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'users', true);

        try {
            $this->userService->deleteUser($userId);
            return response()->json(['message' => 'User deleted successfully.']);
        } catch (\Exception $e) {
            Log::error("Error deleting user: {$e->getMessage()}");
            return new ErrorResponse(collect([
                Error::fromArray([
                    'status' => '500',
                    'title' => 'Internal Server Error',
                    'detail' => $e->getMessage()
                ])
            ]));
        }
    }
}
