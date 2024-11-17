<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class ListarPedidosRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer essa solicitação.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para a solicitação.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'data_ida' => 'nullable|date',
            'data_volta' => 'nullable|date|after_or_equal:data_ida',
            'destino' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:aprovado,cancelado,solicitado',
        ];
    }

    /**
     * Mensagens personalizadas para validação.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'status.in' => __('O status deve ser um dos seguintes valores: aprovado, cancelado, ou solicitado.'),
        ];
    }

    public function failedValidation($validator)
    {
        $errors = $validator->errors();
        
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'Houve um erro na validação dos dados.',
                'errors' => $errors
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
