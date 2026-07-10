<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrador Campus',
                'email' => 'campus@puntoexacto.com',
                'role' => 'Administrador Campus',
            ],
            [
                'name' => 'Operador',
                'email' => 'operador@puntoexacto.com',
                'role' => 'Operador',
            ],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                [
                    'email' => $data['email'],
                ],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('admin123'),
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]
            );

            $user->syncRoles([
                $data['role'],
            ]);
        }
    }
}
