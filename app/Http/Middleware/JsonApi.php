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

        // Check if the input has the 'data' key and 'attributes'
        if (isset($input['data']['attributes'])) {
            $attributes = $input['data']['attributes'];

            // Replace request data with attributes
            $request->replace($attributes);
        }

        return $next($request);
    }
}
