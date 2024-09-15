<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Traits\ExceptionHandlerTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonApi
{
    use ExceptionHandlerTrait;

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
        if (array_key_exists('uid', $input) || array_key_exists('id', $input)) {
            // If 'uid' or 'id' is found outside 'data', throw a JSON:API formatted validation error
            return $this->createError('Invalid Request', 'The uid or id field must be inside the data object.', 400);
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
