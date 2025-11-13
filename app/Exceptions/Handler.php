<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Exceptions that should not be reported.
     */
    protected $dontReport = [];

    /**
     * Register reportable exceptions.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            Log::channel('stack')->error('Unhandled exception', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'url' => request()->fullUrl(),
                'user_id' => auth()->id(),
            ]);
        });
    }

    /**
     * Render a standardized JSON error response.
     */
    public function render($request, Throwable $e)
    {
        if (!$request->expectsJson()) {
            return parent::render($request, $e);
        }

        $traceId = (string) Str::uuid();
        $status = 500;

        $response = [
            'status' => 'error',
            'trace_id' => $traceId,
        ];

        if ($e instanceof ValidationException) {
            $status = 422;
            $response += [
                'error_code' => 'VALIDATION_FAILED',
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ];
        } elseif ($e instanceof AuthenticationException) {
            $status = 401;
            $response += [
                'error_code' => 'UNAUTHENTICATED',
                'message' => 'User is not authenticated.',
            ];
        } elseif ($e instanceof ModelNotFoundException) {
            $status = 404;
            $response += [
                'error_code' => 'RESOURCE_NOT_FOUND',
                'message' => 'The requested resource could not be found.',
            ];
        } elseif ($e instanceof HttpException) {
            $status = $e->getStatusCode();
            $response += [
                'error_code' => 'HTTP_ERROR',
                'message' => $e->getMessage() ?: 'An HTTP error occurred.',
            ];
        } elseif ($e instanceof \DomainException) {
            $status = 400;
            $response += [
                'error_code' => 'DOMAIN_ERROR',
                'message' => $e->getMessage(),
            ];
        } else {
            $response += [
                'error_code' => 'INTERNAL_SERVER_ERROR',
                'message' => 'An unexpected error occurred. Please try again later.',
            ];
        }

        // Centralized structured log
        Log::channel('stack')->error($response['error_code'], [
            'trace_id' => $traceId,
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'user_id' => auth()->id(),
            'url' => $request->fullUrl(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return response()->json($response, $status);
    }
}
