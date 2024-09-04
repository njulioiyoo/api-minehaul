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

        // Check if the input has the 'data' key
        if (isset($input['data'])) {
            $attributes = $input['data']['attributes'] ?? [];
            $id = $input['data']['id'] ?? null;

            // Merge attributes with the original request data
            $requestData = $attributes;

            // dd($requestData);

            // If there's an id, include it in the request data
            if ($id) {
                $requestData['id'] = $id;
            }

            // Replace request data with attributes and id
            $request->merge($requestData);
        }

        return $next($request);
    }
}
