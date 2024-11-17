<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            if ($exception instanceof JWTException) {
                if ($exception->getMessage() == 'Token not provided') {
                    return response()->json([
                        'message' => 'O token de autenticação não foi fornecido.',
                        'status' => 'erro',
                    ], Response::HTTP_UNAUTHORIZED);
                }

                return response()->json([
                    'message' => 'Erro de autenticação JWT. O token pode estar expirado ou inválido.',
                    'status' => 'erro',
                ], Response::HTTP_UNAUTHORIZED);
            }

            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'message' => 'Recurso não encontrado.',
                    'status' => 'erro',
                ], Response::HTTP_NOT_FOUND);
            }

            if ($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'message' => 'A rota que você tentou acessar não existe.',
                    'status' => 'erro',
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'message' => 'Ocorreu um erro inesperado, por favor tente novamente.',
                'error' => $exception->getMessage(),
                'status' => 'erro',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        return parent::render($request, $exception);
    }
}