<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'campus_id' => [
                'required',
                'exists:campuses,id',
            ],

            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'building' => [
                'nullable',
                'string',
                'max:100',
            ],

            'floor' => [
                'nullable',
                'string',
                'max:50',
            ],

            'room' => [
                'nullable',
                'string',
                'max:50',
            ],

            'image' => [
                'nullable',
                'string',
            ],

            'latitude' => [
                'nullable',
                'numeric',
            ],

            'longitude' => [
                'nullable',
                'numeric',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }
}
