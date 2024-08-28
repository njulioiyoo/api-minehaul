<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use LaravelJsonApi\Core\Document\Error;
use LaravelJsonApi\Core\Responses\ErrorResponse;

trait ExceptionHandlerTrait
{
    /**
     * Handle exception and return a consistent error response.
     *
     * @param \Exception $e
     * @param string $message
     * @return ErrorResponse
     */
    protected function handleException(\Exception $e, string $message): ErrorResponse
    {
        Log::error("{$message}: {$e->getMessage()}");

        return new ErrorResponse(collect([
            Error::fromArray([
                'status' => '500',
                'title' => 'Internal Server Error',
                'detail' => $e->getMessage()
            ])
        ]));
    }
}
