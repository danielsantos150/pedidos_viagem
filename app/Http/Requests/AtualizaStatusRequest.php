<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class AtualizaStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status' => 'required|in:' . implode(',', \App\Models\Pedido::getValidStatus()),
            'notificar' => 'nullable|boolean',
        ];
    }

    public function validationData(): array
    {
        return [
            'status' => $this->status,
            'notificar' => $this->has('notificar') ? $this->notificar : false,
        ];
    }


    public function messages()
    {
        return [
            'status.required' => __('validation.required', ['attribute' => 'status']),
            'status.in' => __('validation.in', ['attribute' => 'status']),
            'notificar' => __('validation.nullable', ['attribute' => 'notificar']),
            'notificar' => __('validation.boolean', ['attribute' => 'notificar'])
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
