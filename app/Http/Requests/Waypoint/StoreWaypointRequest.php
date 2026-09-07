<?php

namespace App\Http\Requests\Waypoint;

use Illuminate\Foundation\Http\FormRequest;

class StoreWaypointRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Si no se envía 'is_active', se asume true por defecto.
     * Esto evita depender del default de la base de datos,
     * que en este caso no se estaba aplicando correctamente.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->has('is_active')
                ? $this->boolean('is_active')
                : true,
        ]);
    }

    public function rules(): array
    {
        return [
            'campus_id' => [
                'required',
                'integer',
                'exists:campuses,id',
            ],

            'name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],

            'is_active' => [
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'campus_id.required' => 'El campus es obligatorio.',
            'campus_id.exists' => 'El campus seleccionado no existe.',

            'latitude.required' => 'La latitud es obligatoria.',
            'latitude.between' => 'La latitud no es válida.',

            'longitude.required' => 'La longitud es obligatoria.',
            'longitude.between' => 'La longitud no es válida.',
        ];
    }
}
