<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getAll()
    {
        return User::with('roles')
            ->latest()
            ->get();
    }

    public function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => $data['is_active'],
        ]);

        $user->syncRoles($data['role']);

        return $user->load('roles');
    }

    public function update(User $user, array $data)
    {
        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'is_active' => $data['is_active'],
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        $user->syncRoles($data['role']);

        return $user->fresh()->load('roles');
    }

    public function delete(User $user)
    {
        $user->delete();
    }
}
