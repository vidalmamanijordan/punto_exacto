<?php

namespace App\Http\Requests\Directions;

use Illuminate\Foundation\Http\FormRequest;

class DirectionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'origin_lat' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'origin_lng' => [
                'required',
                'numeric',
                'between:-180,180',
            ],

            'place_id' => [
                'required',
                'integer',
                'exists:places,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'origin_lat.required' => 'La latitud de origen es obligatoria.',
            'origin_lat.between' => 'La latitud de origen no es válida.',

            'origin_lng.required' => 'La longitud de origen es obligatoria.',
            'origin_lng.between' => 'La longitud de origen no es válida.',

            'place_id.required' => 'Debes indicar el lugar de destino.',
            'place_id.exists' => 'El lugar de destino no existe.',
        ];
    }
}
