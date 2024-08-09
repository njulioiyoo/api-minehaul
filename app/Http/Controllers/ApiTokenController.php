<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\CoreApiToken;

class ApiTokenController extends Controller
{
    /**
     * Generate a new API token for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateToken(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Buat token baru
        $apiToken = Str::random(60);

        // Update atau buat token baru
        CoreApiToken::updateOrCreate(
            ['user_id' => $user->id], // Kondisi pencarian
            [
                'session_id' => Str::random(40),
                'url_call' => $request->fullUrl(),
                'api_token' => $apiToken,
            ]
        );

        return response()->json(['api_token' => $apiToken]);
    }
}
