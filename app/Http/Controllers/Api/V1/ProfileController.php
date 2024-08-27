<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\HeaderService;
use App\Services\UserProfile\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $headerService;

    protected $profileService;

    public function __construct(HeaderService $headerService, ProfileService $profileService)
    {
        $this->headerService = $headerService;
        $this->profileService = $profileService;
    }

    public function readProfile(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);
        $queryParams = $request->query();

        return $this->profileService->readProfile(auth()->id(), $queryParams, $headers);
    }

    public function updateProfile(Request $request)
    {
        $headers = $this->headerService->prepareHeaders($request);

        $input = $request->json()->all();
        $input['data']['id'] = (string) auth()->id();
        $input['data']['type'] = 'users';

        $queryParams = $request->query();

        return $this->profileService->updateProfile(auth()->id(), $input, $headers, $queryParams);
    }
}
