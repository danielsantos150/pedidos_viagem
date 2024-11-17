<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class CriarPedidoRequest extends FormRequest
{
    /**
     * Determine se o usuário está autorizado a fazer esta solicitação.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Obtenha as regras de validação que devem ser aplicadas à solicitação.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome_solicitante' => 'required|string|max:255',
            'destino' => 'required|string|max:255',
            'data_ida' => 'required|date',
            'data_volta' => 'required|date|after_or_equal:data_ida',
            'status' => 'required|in:solicitado,aprovado,cancelado',
            'email' => 'required|email|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'solicitante_nome.required' => __('validation.required', ['attribute' => 'nome do solicitante']),
            'solicitante_nome.string' => __('validation.string', ['attribute' => 'nome do solicitante']),
            'solicitante_nome.max' => __('validation.max.string', ['attribute' => 'nome do solicitante', 'max' => 255]),
            'destino.required' => __('validation.required', ['attribute' => 'destino']),
            'destino.string' => __('validation.string', ['attribute' => 'destino']),
            'destino.max' => __('validation.max.string', ['attribute' => 'destino', 'max' => 255]),
            'data_ida.required' => __('validation.required', ['attribute' => 'data de ida']),
            'data_ida.date' => __('validation.date', ['attribute' => 'data de ida']),
            'data_ida.after_or_equal' => __('validation.after_or_equal', ['attribute' => 'data de ida', 'date' => 'hoje']),
            'data_volta.required' => __('validation.required', ['attribute' => 'data de volta']),
            'data_volta.date' => __('validation.date', ['attribute' => 'data de volta']),
            'data_volta.after' => __('validation.after', ['attribute' => 'data de volta', 'date' => 'data de ida']),
            'status.required' => __('validation.required', ['attribute' => 'status']),
            'status.in' => __('validation.in', ['attribute' => 'status', 'values' => 'solicitado, aprovado, cancelado']),
            'email.required'=> __('validation.required', ['attribute' => 'email']),
            'email.max' => __('validation.max.string', ['attribute' => 'email', 'max' => 255]),
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
