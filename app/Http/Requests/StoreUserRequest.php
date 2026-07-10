<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
            ],

            'role' => [
                'required',
                'string',
                'exists:roles,name',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' =>
            'El nombre es obligatorio.',

            'email.required' =>
            'El correo electrónico es obligatorio.',

            'email.email' =>
            'Debe ingresar un correo válido.',

            'email.unique' =>
            'Ya existe un usuario con este correo.',

            'password.required' =>
            'La contraseña es obligatoria.',

            'password.min' =>
            'La contraseña debe tener al menos 8 caracteres.',

            'role.required' =>
            'Debe seleccionar un rol.',

            'role.exists' =>
            'El rol seleccionado no existe.',

            'is_active.required' =>
            'Debe indicar el estado.',
        ];
    }
}
