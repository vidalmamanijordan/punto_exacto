<?php

namespace App\Http\Requests\Path;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StorePathRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 'is_bidirectional' e 'is_active' por defecto en true
     * si no se envían explícitamente.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_bidirectional' => $this->has('is_bidirectional')
                ? $this->boolean('is_bidirectional')
                : true,

            'is_active' => $this->has('is_active')
                ? $this->boolean('is_active')
                : true,
        ]);
    }

    public function rules(): array
    {
        return [
            'from_waypoint_id' => [
                'required',
                'integer',
                'exists:waypoints,id',
            ],

            'to_waypoint_id' => [
                'required',
                'integer',
                'exists:waypoints,id',
                'different:from_waypoint_id',
            ],

            /*
            |--------------------------------------------------------------------------
            | La distancia es opcional: si no se envía, el backend
            | la calcula automáticamente con la fórmula Haversine
            | usando las coordenadas de ambos waypoints.
            |--------------------------------------------------------------------------
            */

            'distance' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'is_bidirectional' => [
                'boolean',
            ],

            'is_active' => [
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'from_waypoint_id.required' => 'El waypoint de origen es obligatorio.',
            'from_waypoint_id.exists' => 'El waypoint de origen no existe.',

            'to_waypoint_id.required' => 'El waypoint de destino es obligatorio.',
            'to_waypoint_id.exists' => 'El waypoint de destino no existe.',
            'to_waypoint_id.different' => 'El waypoint de destino debe ser distinto al de origen.',
        ];
    }

    /**
     * Validación adicional: evita crear un path duplicado
     * entre el mismo par de waypoints.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {

            $exists = \App\Models\Path::where(function ($query) {
                $query->where('from_waypoint_id', $this->input('from_waypoint_id'))
                    ->where('to_waypoint_id', $this->input('to_waypoint_id'));
            })
                ->orWhere(function ($query) {
                    $query->where('from_waypoint_id', $this->input('to_waypoint_id'))
                        ->where('to_waypoint_id', $this->input('from_waypoint_id'));
                })
                ->exists();

            if ($exists) {
                $validator->errors()->add(
                    'to_waypoint_id',
                    'Ya existe un camino entre estos dos waypoints.'
                );
            }
        });
    }
}
