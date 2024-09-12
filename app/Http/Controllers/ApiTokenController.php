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
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateToken(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'You must be logged in to access this resource.',
            ], 401);
        }

        // Buat token baru
        $apiToken = Str::random(60);

        $data = $request->input('data');
        $url = $data['url_accessed'] ?? '';

        if ($url) {
            // Hapus protokol http dan https
            $url = str_replace(['http://', 'https://'], '', $url);

            // Update atau buat token baru
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

        return response()->json(['api_token' => $apiToken]);
    }
}
