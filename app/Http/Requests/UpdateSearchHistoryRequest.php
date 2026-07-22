<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSearchHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id',
            ],
            'place_id' => [
                'required',
                'exists:places,id',
            ],
            'search_text' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Debe seleccionar un usuario.',
            'user_id.exists' => 'El usuario seleccionado no existe.',

            'place_id.required' => 'Debe seleccionar un lugar.',
            'place_id.exists' => 'El lugar seleccionado no existe.',

            'search_text.required' => 'El texto de búsqueda es obligatorio.',
            'search_text.string' => 'El texto de búsqueda debe ser una cadena de texto.',
            'search_text.max' => 'El texto de búsqueda no puede superar los 255 caracteres.',
        ];
    }
}
