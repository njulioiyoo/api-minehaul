<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

trait ExceptionHandlerTrait
{
    /**
     * Handle exception and return a consistent error response.
     */
    protected function handleException(\Exception $e, string $message): JsonResponse
    {
        Log::error("{$message}: {$e->getMessage()}");

        return response()->json([
            'status' => '500',
            'title' => 'Internal Server Error',
            'detail' => $e->getMessage(),
        ], 500);
    }

    public function formatJsonApiResponse(array $data): array
    {
        return [
            'jsonapi' => [
                'version' => '1.0',
            ],
            'data' => $data,
        ];
    }
}
