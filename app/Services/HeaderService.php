<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Request;

class HeaderService
{
    public function prepareHeaders(Request $request)
    {
        return [
            'Accept' => 'application/vnd.api+json',
            'Authorization' => $request->header('Authorization'),
            'x-api-token' => $request->header('x-api-token'),
            'Content-Type' => 'application/vnd.api+json',
        ];
    }
}
