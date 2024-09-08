<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Core\Responses\ErrorResponse;
use App\Traits\ExceptionHandlerTrait;
use App\Http\Requests\System\User\UpdateUserRequest;
use App\Services\System\User\UserService;
use App\Services\RequestHelperService;

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

    public function readProfile(Request $request)
    {
        try {
            $userId = auth()->id();
            // Fetch the user profile directly
            $user = User::find($userId);

            // Transform the user data if needed
            $transformedUser = app('App\Transformers\UserTransformer')->transform($user);

            return response()->json($transformedUser);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error reading user profile');
        }
    }

    /**
     * Update the authenticated user's profile.
     *
     * @param UpdateUserRequest $request
     * @return DataResponse|ErrorResponse
     */
    public function updateProfile(UpdateUserRequest $request)
    {
        try {
            $validatedData = $request->validated();
            [$input, $userId, $queryParams] = $this->requestHelperService->getInputAndId($request, 'users', true);
            // Jika ID pengguna tidak ada dalam permintaan, ambil dari ID otentikasi
            $userId = $userId ?? auth()->id();
            $user = $this->userService->updateUser($userId, $validatedData, []);

            return response()->json($user);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error updating user');
        }
    }
}
