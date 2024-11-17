<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TransactionMiddleware
{
    /**
     * Manipula uma solicitação recebida e executa dentro de uma transação do banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            return DB::transaction(function () use ($request, $next) {
                return $next($request);
            });
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
