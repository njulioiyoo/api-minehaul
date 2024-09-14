<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\CoreApiToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValidateApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request  The incoming HTTP request
     * @param  Closure  $next  The next middleware or request handler
     * @return mixed The response or the next middleware handler
     */
    public function handle(Request $request, Closure $next)
    {
        // Retrieve the API token from the request header
        $apiToken = $request->header('x-api-token');

        // Log for debugging purposes
        Log::info('Received API token:', ['apiToken' => $apiToken]);

        // Return an error response if the API token is not provided
        if (! $apiToken) {
            return response()->json(['message' => 'API token is required'], 401);
        }

        // Decode the URL to handle encoded characters and remove HTTP/HTTPS protocols
        $url = urldecode($request->fullUrl());
        $baseUrl = preg_replace('/^https?:\/\//', '', $url);

        // Check if the token exists in the database and is valid for the current URL
        $tokenRecord = CoreApiToken::where([
            'api_token' => $apiToken,
            'url_accessed' => $baseUrl,
        ])->first();

        // Log the token record for debugging purposes
        Log::info('Token record:', ['tokenRecord' => $tokenRecord]);

        // Return an error response if the token is not valid
        if (! $tokenRecord) {
            return response()->json(['message' => 'Invalid API token'], 403);
        }

        // Proceed to the next middleware or request handler if the token is valid
        return $next($request);
    }
}
