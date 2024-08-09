<?php

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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Ambil token dari header request
        $apiToken = $request->header('x-api-token');

        // Log untuk debugging
        Log::info('Received API token:', ['apiToken' => $apiToken]);

        if (!$apiToken) {
            return response()->json(['message' => 'API token is required'], 401);
        }

        // Cek apakah token ada di database dan valid
        $tokenRecord = CoreApiToken::where('api_token', $apiToken)->first();

        // Log token record untuk debugging
        Log::info('Token record:', ['tokenRecord' => $tokenRecord]);

        if (!$tokenRecord) {
            return response()->json(['message' => 'Invalid API token'], 403);
        }

        return $next($request);
    }
}
