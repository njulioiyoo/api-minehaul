<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

trait ExceptionHandlerTrait
{
    /**
     * Handle exception and return a consistent error response.
     */
    protected function handleException(\Exception $e, string $message): JsonResponse
    {
        Log::error("{$message}: {$e->getMessage()}");

        return $this->createError(
            'Internal Server Error',
            $e->getMessage(),
            500
        );
    }

    /**
     * Create a JSON API error response.
     */
    public function createError(string $title, string $detail, int $status): JsonResponse
    {
        return response()->json([
            'jsonapi' => $this->getJsonApiVersion(),
            'errors' => [
                [
                    'title' => $title,
                    'detail' => $detail,
                    'status' => $status,
                ],
            ],
        ], $status);
    }

    /**
     * Format JSON API success response.
     */
    public function formatJsonApiResponse(array $data): array
    {
        return [
            'jsonapi' => $this->getJsonApiVersion(),
            'data' => $data,
        ];
    }

    /**
     * Get the JSON:API version.
     */
    public function getJsonApiVersion(): array
    {
        return [
            'version' => '1.0',
        ];
    }
}
