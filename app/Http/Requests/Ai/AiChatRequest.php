<?php

namespace App\Http\Requests\Ai;

use Illuminate\Foundation\Http\FormRequest;

class AiChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => [
                'required',
                'string',
                'min:2',
                'max:500',
            ],

            'campus_id' => [
                'nullable',
                'integer',
                'exists:campuses,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'El mensaje es obligatorio.',
            'message.string' => 'El mensaje debe ser texto.',
            'message.min' => 'El mensaje debe tener al menos :min caracteres.',
            'message.max' => 'El mensaje no puede superar los :max caracteres.',

            'campus_id.integer' => 'El campus seleccionado no es válido.',
            'campus_id.exists' => 'El campus seleccionado no existe.',
        ];
    }
}
