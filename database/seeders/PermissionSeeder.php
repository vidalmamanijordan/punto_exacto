<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Campus
            'campus.view',
            'campus.create',
            'campus.edit',
            'campus.delete',

            // Categories
            'category.view',
            'category.create',
            'category.edit',
            'category.delete',

            // Places
            'place.view',
            'place.create',
            'place.edit',
            'place.delete',

            // FAQs
            'faq.view',
            'faq.create',
            'faq.edit',
            'faq.delete',

            // Users
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',

            // Roles
            'role.view',
            'role.create',
            'role.edit',
            'role.delete',

            // Dashboard
            'dashboard.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        $adminGeneral = Role::findByName(
            'Administrador General'
        );

        $adminCampus = Role::findByName(
            'Administrador Campus'
        );

        $operador = Role::findByName(
            'Operador'
        );

        $adminGeneral->givePermissionTo(
            Permission::all()
        );

        $adminCampus->givePermissionTo([
            'campus.view',
            'category.view',
            'place.view',
            'place.create',
            'place.edit',
            'faq.view',
            'faq.create',
            'faq.edit',
            'dashboard.view',
        ]);

        $operador->givePermissionTo([
            'place.view',
            'faq.view',
            'dashboard.view',
        ]);
    }
}
