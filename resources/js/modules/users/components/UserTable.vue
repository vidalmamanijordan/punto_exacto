<script setup lang="ts">
import {
    Pencil,
    Trash2,
    User2,
} from '@lucide/vue';
import { Button } from '@/components/ui/button';
import type { User } from '../types/user';

defineProps<{
    users: User[];
    loading?: boolean;
}>();

const emit = defineEmits<{
    edit: [User];
    delete: [User];
}>();

function formatDate(date: string) {
    const d = new Date(date);

    return {
        date: d.toLocaleDateString('es-PE', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        }),
        time: d.toLocaleTimeString('es-PE', {
            hour: '2-digit',
            minute: '2-digit',
        }),
    };
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
                        Usuario
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest text-slate-400">
                        Correo
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest text-slate-400">
                        Rol
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest text-slate-400">
                        Estado
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest text-slate-400">
                        Creado
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-medium uppercase tracking-widest text-slate-400">
                        Acciones
                    </th>

                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">

                <!-- Loading -->

                <tr v-if="loading">
                    <td colspan="7" class="py-20 text-center">

                        <div class="flex flex-col items-center gap-3">

                            <div
                                class="h-6 w-6 animate-spin rounded-full border-2 border-slate-200 border-t-slate-500" />

                            <p class="text-sm text-slate-500">
                                Cargando usuarios...
                            </p>

                        </div>

                    </td>
                </tr>

                <!-- Sin datos -->

                <tr v-else-if="users.length === 0">

                    <td colspan="7" class="py-20 text-center">

                        <div class="flex flex-col items-center gap-3">

                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50 ring-1 ring-slate-100">

                                <User2 class="h-5 w-5 text-slate-300" />

                            </div>

                            <div>

                                <p class="text-sm font-medium text-slate-500">
                                    Sin usuarios registrados
                                </p>

                                <p class="mt-0.5 text-xs text-slate-400">
                                    No existen usuarios registrados aún.
                                </p>

                            </div>

                        </div>

                    </td>

                </tr>

                <!-- Datos -->

                <tr v-for="(user, index) in users" :key="user.id"
                    class="group border-l-2 border-l-transparent transition-all duration-200 hover:border-l-slate-300 hover:bg-slate-50/60">

                    <td class="px-5 py-4 text-center">

                        <span class="font-mono text-xs text-slate-300">
                            {{ String(index + 1).padStart(2, '0') }}
                        </span>

                    </td>

                    <td class="px-6 py-4">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-xs font-semibold text-slate-500">

                                {{ user.name.charAt(0).toUpperCase() }}

                            </div>

                            <span class="text-sm font-semibold text-slate-700">

                                {{ user.name }}

                            </span>

                        </div>

                    </td>

                    <td class="px-6 py-4">

                        <span class="text-sm text-slate-500">

                            {{ user.email }}

                        </span>

                    </td>

                    <td class="px-6 py-4">

                        <div class="flex flex-wrap gap-1.5">
                            <span v-for="role in user.roles" :key="role.id"
                                class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-600 ring-1 ring-blue-100">
                                {{ role.name }}
                            </span>

                            <span v-if="user.roles.length === 0" class="text-xs italic text-slate-300">
                                Sin rol
                            </span>
                        </div>

                    </td>

                    <td class="px-6 py-4">

                        <span v-if="user.is_active"
                            class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-600 ring-1 ring-emerald-100">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400" />

                            Activo
                        </span>

                        <span v-else
                            class="inline-flex items-center gap-2 rounded-full bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-400 ring-1 ring-slate-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-slate-300" />

                            Inactivo
                        </span>

                    </td>

                    <td class="px-6 py-4">

                        <div class="flex flex-col">

                            <span class="text-sm text-slate-600">

                                {{ formatDate(user.created_at).date }}

                            </span>

                            <span class="text-xs text-slate-400">

                                {{ formatDate(user.created_at).time }}

                            </span>

                        </div>

                    </td>

                    <td class="px-6 py-4">

                        <div class="flex items-center justify-center gap-1.5">

                            <Button variant="ghost" size="icon"
                                class="h-8 w-8 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600"
                                @click="emit('edit', user)">
                                <Pencil class="h-3.5 w-3.5" />
                            </Button>

                            <Button variant="ghost" size="icon"
                                class="h-8 w-8 rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-500"
                                @click="emit('delete', user)">
                                <Trash2 class="h-3.5 w-3.5" />
                            </Button>

                        </div>

                    </td>

                </tr>

            </tbody>

        </table>
    </div>
</template>
