<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CoreApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ApiTokenController extends Controller
{
    /**
     * Generate a new API token for the authenticated user.
     *
     * @param  Request  $request  The incoming HTTP request
     * @return \Illuminate\Http\JsonResponse The JSON response with the generated API token
     */
    public function generateToken(Request $request)
    {
        // Retrieve the currently authenticated user
        $user = Auth::user();
        if (! $user) {
            // Return unauthorized response if user is not authenticated
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'You must be logged in to access this resource.',
            ], 401);
        }

        // Generate a new API token
        $apiToken = Str::random(60);

        // Retrieve the accessed URL from the request input
        $url = $request->input('data.url_accessed', '');

        if ($url) {
            // Remove the http and https protocols from the URL
            $url = str_replace(['http://', 'https://'], '', $url);

            // Update or create a new API token record in the database
            CoreApiToken::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'url_accessed' => $url,
                ],
                [
                    'session_id' => Str::random(40),
                    'url_call' => $request->fullUrl(),
                    'api_token' => $apiToken,
                ]
            );
        }

        // Return the newly generated API token in the response
        return response()->json(['api_token' => $apiToken]);
    }
}
