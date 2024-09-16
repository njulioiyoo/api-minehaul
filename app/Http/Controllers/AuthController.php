<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Traits\ExceptionHandlerTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ExceptionHandlerTrait;

    /**
     * Retrieve the OAuth client that is marked as 'password_client' from the database.
     *
     * @return mixed
     */
    private function getPasswordClient()
    {
        return DB::table('oauth_clients')->where('password_client', 1)->first();
    }

    /**
     * Handle the OAuth token request by sending necessary parameters to the /oauth/token endpoint.
     * This method can be reused for different OAuth grant types (password, refresh_token, etc.).
     *
     * @return Response|JsonResponse
     */
    private function handleOAuthTokenRequest(array $params)
    {
        // Retrieve the OAuth client
        $client = $this->getPasswordClient();

        // If no client is found, return an error response
        if (! $client) {
            return $this->createError('Client not found', 'The password client could not be found.', Response::HTTP_BAD_REQUEST);
        }

        // Add client credentials to the request parameters
        $params['client_id'] = $client->id;
        $params['client_secret'] = $client->secret;

        // Make a request to the /oauth/token endpoint with the given parameters
        $response = app()->handle(
            Request::create(config('app.url').'/oauth/token', 'POST', $params)
        );

        // Check if the response status is OK, otherwise return an authentication error
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return $this->createError('Authentication Failed', 'Invalid credentials or other authentication error.', Response::HTTP_BAD_REQUEST);
        }

        // Return the response if everything is fine
        return $response;
    }

    /**
     * Login the user by verifying credentials and company code.
     * If successful, return an OAuth token response.
     */
    public function login(LoginRequest $request): Response
    {
        // Retrieve the user and check if the user belongs to the correct company
        $user = User::where('username', $request->input('username'))
            ->with(['persons.account' => function ($query) use ($request) {
                $query->where('company_code', $request->input('company_code'));
            }])->first();

        // Return an error if user, person, or account is not found
        if (! $user || ! $user->persons || ! $user->persons->account) {
            return $this->createError('Invalid Data', 'Invalid User or Company ID.', Response::HTTP_BAD_REQUEST);
        }

        // Check if the password matches
        if (! Hash::check($request->input('password'), $user->password)) {
            return $this->createError('Authentication Failed', 'Sorry, your Password is wrong.', Response::HTTP_BAD_REQUEST);
        }

        // Handle OAuth token request and return the token
        return $this->handleOAuthTokenRequest([
            'grant_type' => 'password',
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'scope' => '',
        ]);
    }

    /**
     * Refresh the OAuth token using the refresh_token grant type.
     */
    public function refreshToken(Request $request): JsonResponse
    {
        // Validate that the refresh token is provided in the request
        $request->validate([
            'refresh_token' => 'required',
        ]);

        // Handle OAuth token request using the refresh token grant and return the token
        return response()->json(json_decode($this->handleOAuthTokenRequest([
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->input('refresh_token'),
            'scope' => '',
        ])->getContent(), true));
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke(); // Cabut token akses saat ini

        return response()->json(['message' => 'Successfully logged out']);
    }
}
