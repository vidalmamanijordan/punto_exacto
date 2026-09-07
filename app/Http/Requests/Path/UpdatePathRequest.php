<?php

namespace App\Http\Requests\Path;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePathRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'distance' => [
                'sometimes',
                'required',
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
}
