<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get JSON body
        $input = $request->json()->all();

        // Check if 'uid' or 'id' is outside 'data'
        if (isset($input['uid']) || isset($input['id'])) {
            // If 'uid' or 'id' is found outside 'data', throw a JSON:API formatted validation error
            return response()->json([
                'jsonapi' => [
                    'version' => '1.0',
                ],
                'errors' => [
                    [
                        'status' => '400',
                        'title' => 'Invalid Request',
                        'detail' => 'The uid or id field must be inside the data object.',
                        'source' => [
                            'pointer' => isset($input['uid']) ? '/uid' : '/id',
                        ],
                    ],
                ],
            ], 400);
        }

        // If 'data' exists, continue processing
        if (isset($input['data'])) {
            $attributes = $input['data']['attributes'] ?? [];
            $uid = $input['data']['uid'] ?? null;
            $id = $input['data']['id'] ?? null;

            // Merge attributes with uid and id
            $requestData = array_merge(
                $attributes,
                array_filter([
                    'uid' => $uid,
                    'id' => $id,
                ])
            );

            // Replace request data with attributes, uid, and id
            $request->merge($requestData);
        }

        return $next($request);
    }
}
