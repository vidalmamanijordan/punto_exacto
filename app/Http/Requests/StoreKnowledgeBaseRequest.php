<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKnowledgeBaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'content' => [
                'required',
                'string',
            ],

            'is_active' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'title.string' => 'El título debe ser un texto válido.',
            'title.max' => 'El título no puede superar los 255 caracteres.',
            'content.required' => 'El contenido es obligatorio.',
            'content.string' => 'El contenido debe ser un texto válido.',
            'is_active.boolean' => 'El estado debe ser verdadero o falso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'título',
            'content' => 'contenido',
            'is_active' => 'estado',
        ];
    }
}
