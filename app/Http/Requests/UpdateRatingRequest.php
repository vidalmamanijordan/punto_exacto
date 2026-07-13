<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRatingRequest extends FormRequest
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

            'rating' => [
                'required',
                'integer',
                'between:1,5',
            ],

            'comment' => [
                'nullable',
                'string',
                'max:1000',
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

            'rating.required' => 'Debe seleccionar una calificación.',
            'rating.integer' => 'La calificación es inválida.',
            'rating.between' => 'La calificación debe estar entre 1 y 5 estrellas.',

            'comment.max' => 'El comentario no puede superar los 1000 caracteres.',
        ];
    }
}
