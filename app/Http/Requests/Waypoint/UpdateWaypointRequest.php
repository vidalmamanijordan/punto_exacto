<?php

namespace App\Http\Requests\Waypoint;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWaypointRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'campus_id' => [
                'sometimes',
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
                'sometimes',
                'required',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'sometimes',
                'required',
                'numeric',
                'between:-180,180',
            ],

            'is_active' => [
                'boolean',
            ],
        ];
    }
}
