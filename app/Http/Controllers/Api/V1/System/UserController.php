<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Controller;
use App\Services\HeaderService;
use App\Services\RequestHelperService;
use App\Services\System\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $headerService;

    protected $userService;

    protected $requestHelperService;

    public function __construct(HeaderService $headerService, UserService $userService, RequestHelperService $requestHelperService)
    {
        $this->headerService = $headerService;
        $this->userService = $userService;
        $this->requestHelperService = $requestHelperService;
    }

    public function createUser(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);
        [$input, $userId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'users');

        return $this->userService->createUser($input, $headers, $queryParams);
    }

    public function readUser(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);
        $queryParams = $request->query();

        return $this->userService->readUser($queryParams, $headers);
    }

    public function updateUser(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);
        [$input, $userId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'users');

        // Panggil metode updateDevice dengan ID yang diperoleh
        return $this->userService->updateUser($userId, $input, $headers, $queryParams);
    }

    public function deleteUser(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);
        [$input, $userId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'users');

        // Panggil metode deleteDevice dengan ID yang diperoleh
        return $this->userService->deleteUser($userId, $input, $headers, $queryParams);
    }
}
