<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\System\User\UpdateUserRequest;
use App\Models\User;
use App\Services\RequestHelperService;
use App\Services\System\User\UserService;
use App\Traits\ExceptionHandlerTrait;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Core\Responses\ErrorResponse;

class ProfileController extends Controller
{
    use ExceptionHandlerTrait;

    protected $userService;

    protected $requestHelperService;

    public function __construct(UserService $userService, RequestHelperService $requestHelperService)
    {
        $this->userService = $userService;
        $this->requestHelperService = $requestHelperService;
    }

    public function readProfile()
    {
        try {
            $userId = auth()->id();
            // Fetch the user profile directly
            $user = User::find($userId);

            // Transform the user data if needed
            return $this->formatJsonApiResponse(
                app('App\Transformers\UserTransformer')->transform($user)
            );
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error reading user profile');
        }
    }

    /**
     * Update the authenticated user's profile.
     *
     * @return DataResponse|ErrorResponse
     */
    public function updateProfile(UpdateUserRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $user = $this->userService->updateUser((string) auth()->id(), $validatedData, []);

            return response()->json($user);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error updating user');
        }
    }
}
