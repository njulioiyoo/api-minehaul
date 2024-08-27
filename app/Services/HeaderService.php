<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Request;

class HeaderService
{
    public function prepareHeaders(Request $request)
    {
        $authorizationHeader = $request->header('Authorization');
        if ($authorizationHeader && !str_starts_with($authorizationHeader, 'Bearer ')) {
            $authorizationHeader = 'Bearer ' . $authorizationHeader;
        }

        return [
            'Accept' => 'application/vnd.api+json',
            'Authorization' => $authorizationHeader, // Gunakan header Authorization dengan Bearer token
            'x-api-token' => $request->header('x-api-token'),
            'Content-Type' => 'application/vnd.api+json',
        ];
    }
}
