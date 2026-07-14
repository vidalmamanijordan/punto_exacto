<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'place_id' => [
                'required',
                'exists:places,id',
                Rule::unique('favorites')
                    ->where(fn($query) => $query->where('user_id', $this->user_id))
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
            'place_id.unique' => 'Este lugar ya se encuentra registrado como favorito para el usuario seleccionado.',
        ];
    }
}
