<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Str;

class Handler extends ExceptionHandler
{
    /**
     * Lista de exceções que não devem ser reportadas no log
     */
    protected $dontReport = [
        // Adicione exceções que não precisam de log, se quiser
    ];

    /**
     * Registra callbacks para tratamento de exceções.
     */
    public function register(): void
    {
        //
    }

    /**
     * Renderiza a resposta HTTP em formato JSON padronizado.
     */
    public function render($request, Throwable $e)
    {
        // Se não for uma API, deixa o comportamento padrão
        if (!$request->expectsJson()) {
            return parent::render($request, $e);
        }

        // Define estrutura base da resposta
        $response = [
            'status' => 'error',
            'trace_id' => Str::uuid()->toString(),
        ];

        // Define tipo de erro e mensagem
        if ($e instanceof ValidationException) {
            $response['message'] = 'Validation failed.';
            $response['errors'] = $e->errors();
            $status = 422;
        } elseif ($e instanceof AuthenticationException) {
            $response['message'] = 'Unauthenticated.';
            $status = 401;
        } elseif ($e instanceof ModelNotFoundException) {
            $response['message'] = 'Resource not found.';
            $response['error_code'] = 'NOT_FOUND';
            $status = 404;
        } elseif ($e instanceof HttpException) {
            $response['message'] = $e->getMessage() ?: 'HTTP error.';
            $status = $e->getStatusCode();
        } elseif ($e instanceof \DomainException) {
            $response['message'] = $e->getMessage();
            $response['error_code'] = 'DOMAIN_ERROR';
            $status = 400;
        } else {
            $response['message'] = 'Internal server error.';
            $response['error_code'] = 'INTERNAL_ERROR';
            $status = 500;
        }

        // Loga com trace_id (útil pra rastrear erros em produção)
        \Log::error($e->getMessage(), [
            'trace_id' => $response['trace_id'],
            'exception' => $e
        ]);

        return response()->json($response, $status);
    }
}
