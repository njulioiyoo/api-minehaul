<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use LaravelJsonApi\Core\Document\Error;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(LoginRequest $request): Response|Error
    {
        // Ambil client password dari database
        $client = DB::table('oauth_clients')->where('password_client', 1)->first();

        if (! $client) {
            return Error::fromArray([
                'title' => 'Client not found',
                'detail' => 'The password client could not be found.',
                'status' => Response::HTTP_BAD_REQUEST,
            ]);
        }

        // Verifikasi kredensial pengguna dan account_id dalam satu kueri
        $user = User::where('email', $request->input('email'))
            ->whereHas('account', function ($query) use ($request) {
                $query->where('company_id', $request->input('account_id'));
            })
            ->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            return Error::fromArray([
                'title' => 'Authentication Failed',
                'detail' => 'Invalid credentials or other authentication error.',
                'status' => Response::HTTP_BAD_REQUEST,
            ]);
        }

        // Buat permintaan token ke endpoint oauth/token
        $response = app()->handle(
            Request::create(config('app.url').'/oauth/token', 'POST', [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $request->input('email'),
                'password' => $request->input('password'),
                'scope' => '',
            ])
        );

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return Error::fromArray([
                'title' => 'Authentication Failed',
                'detail' => 'Invalid credentials or other authentication error.',
                'status' => Response::HTTP_BAD_REQUEST,
            ]);
        }

        return $response;
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke(); // Cabut token akses saat ini

        return response()->json(['message' => 'Successfully logged out']);
    }
}
