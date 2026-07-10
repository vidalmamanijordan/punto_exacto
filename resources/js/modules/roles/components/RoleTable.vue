<script setup lang="ts">
import {
    Pencil,
    Trash2,
    Shield,
} from '@lucide/vue';
import { Button } from '@/components/ui/button';

type Permission = {
    id: number;
    name: string;
};

export type Role = {
    id: number;
    name: string;
    permissions: string[] | Permission[];
    permissions_count: number;
};

defineProps<{
    roles: Role[];
    loading?: boolean;
}>();

const emit = defineEmits<{
    edit: [Role];
    delete: [Role];
}>();

const MAX_VISIBLE_PERMISSIONS = 3;

function isObjectPermissions(
    perms: any
): perms is Permission[] {
    return (
        Array.isArray(perms) &&
        typeof perms[0] === 'object'
    );
}

function getPermissionName(perm: string | Permission): string {
    return typeof perm === 'object' ? perm.name : perm;
}

function visiblePermissions(perms: string[] | Permission[]): (string | Permission)[] {
    return (perms as (string | Permission)[]).slice(0, MAX_VISIBLE_PERMISSIONS);
}

function hiddenCount(perms: string[] | Permission[]): number {
    return Math.max(0, perms.length - MAX_VISIBLE_PERMISSIONS);
}
</script>

<template>
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/80">
                    <th class="w-12 px-5 py-4 text-center text-xs font-medium uppercase tracking-widest text-slate-400">
                        #
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest text-slate-400">
                        Rol
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest text-slate-400">
                        Permisos
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest text-slate-400">
                        Cantidad
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-medium uppercase tracking-widest text-slate-400">
                        Acciones
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                <!-- Loading -->
                <tr v-if="loading">
                    <td colspan="5" class="py-20 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div
                                class="h-6 w-6 animate-spin rounded-full border-2 border-slate-200 border-t-slate-500" />

                            <p class="text-sm text-slate-500">
                                Cargando roles...
                            </p>
                        </div>
                    </td>
                </tr>

                <!-- Empty -->
                <tr v-else-if="
                    roles.length === 0
                ">
                    <td colspan="5" class="py-20 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50 ring-1 ring-slate-100">
                                <Shield class="h-5 w-5 text-slate-300" />
                            </div>

                            <div>
                                <p class="text-sm font-medium text-slate-500">
                                    Sin roles registrados
                                </p>

                                <p class="mt-0.5 text-xs text-slate-400">
                                    No existen roles
                                    registrados aún.
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>

                <!-- Data -->
                <tr v-for="(
role, index
                    ) in roles" v-else :key="role.id"
                    class="group border-l-2 border-l-transparent transition-all duration-200 hover:border-l-slate-300 hover:bg-slate-50/60">
                    <!-- Número -->
                    <td class="px-5 py-4 text-center">
                        <span class="font-mono text-xs text-slate-300">
                            {{
                                String(
                                    index + 1
                                ).padStart(
                                    2,
                                    '0'
                                )
                            }}
                        </span>
                    </td>

                    <!-- Rol -->
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100">
                                <Shield class="h-4 w-4 text-slate-500" />
                            </div>

                            <span class="text-sm font-medium text-slate-700">
                                {{
                                    role.name
                                }}
                            </span>
                        </div>
                    </td>

                    <!-- Permisos -->
                    <td class="max-w-xs px-6 py-4">
                        <div class="flex flex-wrap gap-1.5">
                            <span v-for="perm in visiblePermissions(role.permissions)" :key="getPermissionName(perm)"
                                class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">
                                {{ getPermissionName(perm) }}
                            </span>

                            <span v-if="hiddenCount(role.permissions) > 0"
                                class="rounded-full bg-slate-200 px-2.5 py-0.5 text-xs font-medium text-slate-500">
                                +{{ hiddenCount(role.permissions) }}
                            </span>

                            <span v-if="role.permissions.length === 0" class="text-xs text-slate-300 italic">
                                Sin permisos
                            </span>
                        </div>
                    </td>

                    <!-- Cantidad -->
                    <td class="px-6 py-4">
                        <span
                            class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-600 ring-1 ring-blue-100">
                            {{
                                role.permissions_count
                            }}
                            permisos
                        </span>
                    </td>

                    <!-- Acciones -->
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <Button @click="
                                emit(
                                    'edit',
                                    role
                                )
                                " variant="ghost" size="icon"
                                class="h-8 w-8 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                                <Pencil class="h-3.5 w-3.5" />
                            </Button>

                            <Button @click="
                                emit(
                                    'delete',
                                    role
                                )
                                " variant="ghost" size="icon"
                                class="h-8 w-8 rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-500">
                                <Trash2 class="h-3.5 w-3.5" />
                            </Button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
