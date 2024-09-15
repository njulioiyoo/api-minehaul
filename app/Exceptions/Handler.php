<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Traits\ExceptionHandlerTrait;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use ExceptionHandlerTrait;

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            dd($request);

            return response()->json([
                'jsonapi' => [
                    'version' => '1.0',
                ],
                'errors' => [
                    [
                        'status' => '405',
                        'title' => 'Method Not Allowed',
                        'detail' => 'The method is not allowed for the requested URL.',
                    ],
                ],
            ], 405);
        });
    }
}
