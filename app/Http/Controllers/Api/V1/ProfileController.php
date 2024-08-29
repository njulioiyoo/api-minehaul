<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\UserProfile\ProfileService;
use Illuminate\Http\Request;
use LaravelJsonApi\Core\Responses\DataResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use LaravelJsonApi\Core\Document\Error;
use LaravelJsonApi\Core\Responses\ErrorResponse;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function readProfile(Request $request)
    {
        $userId = auth()->id(); // Get the authenticated user ID

        try {
            // Fetch the user profile directly from the service
            $user = $this->profileService->readProfile($userId);

            // Return a DataResponse with the user data
            return new DataResponse($user);
        } catch (\Exception $e) {
            // Log the error
            Log::error("Error reading profile: {$e->getMessage()}");

            // Return a generic error response
            return new ErrorResponse(collect([
                Error::fromArray([
                    'status' => '404',
                    'title' => 'Not Found',
                    'detail' => 'The user profile could not be found.'
                ])
            ]));
        }
    }

    /**
     * Update the authenticated user's profile.
     *
     * @param Request $request
     * @return DataResponse|ErrorResponse
     */
    public function updateProfile(Request $request)
    {
        try {
            // Validate and update the profile through the service
            $user = $this->profileService->updateProfile($request->all());

            // Return a DataResponse with the updated user data
            return new DataResponse($user);
        } catch (ValidationException $e) {
            // Log the error
            Log::error("ValidationException: {$e->getMessage()}");

            // Return JSON:API Error Response using the service method
            return $this->profileService->formatValidationErrors($e->validator);
        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error("Unexpected Exception: {$e->getMessage()}");

            // Return a generic error response
            return new ErrorResponse(collect([
                Error::fromArray([
                    'status' => '500',
                    'title' => 'Unexpected Error',
                    'detail' => 'An unexpected error occurred.'
                ])
            ]));
        }
    }
}
