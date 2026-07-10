<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:campuses,name',
            ],
            'code' => [
                'required',
                'string',
                'nullable',
                'max:10',
                'unique:campuses,code'
            ],
            'address' => [
                'nullable',
                'string',
                'max:255',
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
