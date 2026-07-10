<?php

namespace App\Services;

use Spatie\Permission\Models\Role;

class RoleService
{
    public function getAll()
    {
        return Role::with('permissions')
            ->orderBy('name')
            ->get();
    }

    public function create(array $data)
    {
        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);

        $role->syncPermissions(
            $data['permissions'] ?? []
        );

        return $role->load('permissions');
    }

    public function update(
        Role $role,
        array $data
    ) {
        $role->update([
            'name' => $data['name'],
        ]);

        $role->syncPermissions(
            $data['permissions'] ?? []
        );

        return $role->load('permissions');
    }

    public function delete(Role $role)
    {
        $role->delete();
    }
}
