<?php

namespace App\Helpers;

class ResponseHelper
{
    /**
     * Formata a resposta com o campo 'data'.
     *
     * @param mixed $data Dados que serão retornados dentro do campo 'data'.
     * @param string $message Mensagem da resposta.
     * @param int $status Status HTTP da resposta.
     * @return \Illuminate\Http\JsonResponse
     */
    public static function formatResponse($data = null, $message = 'Request processado com sucesso.', $status = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}
