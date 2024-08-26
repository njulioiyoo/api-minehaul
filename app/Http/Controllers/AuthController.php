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
use LaravelJsonApi\Core\Responses\DataResponse;

class AuthController extends Controller
{
    public function login(LoginRequest $request): Response|Error
    {
        // Ambil client password dari database
        $client = DB::table('oauth_clients')->where('password_client', 1)->first();

        if (! $client) {
            return $this->createError('Client not found', 'The password client could not be found.', Response::HTTP_BAD_REQUEST);
        }

        // Verifikasi kredensial pengguna dan person_id dalam satu kueri
        $user = User::select('id', 'username', 'person_id', 'email', 'password')->where('username', $request->input('username'))
            ->with(['persons.account' => function ($query) use ($request) {
                $query->select('id', 'company_code')->where('company_code', '=', $request->input('company_code'));
            }])->first();

        // Cek apakah user ditemukan
        if (! $user) {
            return $this->createError('User not found', 'Sorry, your User ID is not registered.', Response::HTTP_BAD_REQUEST);
        }

        // Cek apakah user memiliki person dan account yang sesuai
        if (! $user->persons || ! $user->persons->account) {
            return $this->createError('Invalid Data', 'Sorry, your Company ID is not registered.', Response::HTTP_BAD_REQUEST);
        }

        // Verifikasi password
        if (! Hash::check($request->input('password'), $user->password)) {
            return $this->createError('Authentication Failed', 'Sorry, your Password is wrong.', Response::HTTP_BAD_REQUEST);
        }

        // Buat permintaan token ke endpoint oauth/token
        $response = app()->handle(
            Request::create(config('app.url') . '/oauth/token', 'POST', [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $request->input('username'),
                'password' => $request->input('password'),
                'scope' => '',
            ])
        );

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return $this->createError('Authentication Failed', 'Invalid credentials or other authentication error.', Response::HTTP_BAD_REQUEST);
        }

        return $response;
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke(); // Cabut token akses saat ini

        return response()->json(['message' => 'Successfully logged out']);
    }

    private function createError(string $title, string $detail, int $status): Error
    {
        return Error::fromArray([
            'title' => $title,
            'detail' => $detail,
            'status' => $status,
        ]);
    }
}
