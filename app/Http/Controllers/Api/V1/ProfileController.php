<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\System\User\UpdateUserRequest;
use App\Models\User;
use App\Services\RequestHelperService;
use App\Services\System\Role\RoleService;
use App\Services\System\User\UserService;
use App\Traits\ExceptionHandlerTrait;
use Illuminate\Http\Request;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Core\Responses\ErrorResponse;
use Spatie\Permission\Models\Role;

class ProfileController extends Controller
{
    use ExceptionHandlerTrait;

    protected $userService;

    protected $requestHelperService;

    protected $roleService;

    public function __construct(UserService $userService, RequestHelperService $requestHelperService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->requestHelperService = $requestHelperService;
        $this->roleService = $roleService;
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

    public function roleAccess(Request $request)
    {
        try {
            $queryParams = $request->query();

            $role = Role::findOrFail($queryParams['id']);

            return $this->formatJsonApiResponse(
                app('App\Transformers\RoleTransformer')->transform($role)
            );
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error fetching role data');
        }
    }
}
