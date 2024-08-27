<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Controller;
use App\Services\HeaderService;
use App\Services\RequestHelperService;
use App\Services\System\User\UserService;
use Illuminate\Http\Request;
use LaravelJsonApi\Core\Responses\ErrorResponse;
use Illuminate\Support\Facades\Log;
use LaravelJsonApi\Core\Document\Error;

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
        // $headers = $this->headerService->prepareHeaders($request);
        // $queryParams = $request->query();

        // return $this->userService->readUserV2($queryParams, $headers);

        $queryParams = $request->query();

        try {
            $response = $this->userService->readUser($queryParams);
            return response()->json($response);
        } catch (\Exception $e) {
            dd($e);
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

    public function updateUser(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);
        [$input, $userId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'users', true);

        // Panggil metode updateDevice dengan ID yang diperoleh
        return $this->userService->updateUser($userId, $input, $headers, $queryParams);
    }

    public function deleteUser(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);
        [$input, $userId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'users', true);

        // Panggil metode deleteDevice dengan ID yang diperoleh
        return $this->userService->deleteUser($userId, $input, $headers, $queryParams);
    }
}
