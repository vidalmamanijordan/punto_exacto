<script setup lang="ts">
import { Building2, Pencil, Trash2 } from '@lucide/vue';
import { Button } from '@/components/ui/button';

import type { Campus } from '../types/campus';

defineProps<{
    campuses: Campus[];
}>();

const emit = defineEmits<{
    edit: [Campus];
    delete: [Campus];
}>();

function formatDate(dateString: string): {
    date: string;
    time: string;
} {
    const d = new Date(dateString);

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
                        Nombre
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest text-slate-400">
                        Código
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest text-slate-400">
                        Dirección
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest text-slate-400">
                        Coordenadas
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
                <tr v-for="(campus, index) in campuses" :key="campus.id"
                    class="group border-l-2 border-l-transparent transition-all duration-200 hover:border-l-slate-300 hover:bg-slate-50/60">
                    <td class="px-5 py-4 text-center">
                        <span class="font-mono text-xs text-slate-300">
                            {{
                                String(index + 1).padStart(
                                    2,
                                    '0'
                                )
                            }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-xs font-semibold text-slate-500">
                                {{
                                    campus.name
                                        .charAt(0)
                                        .toUpperCase()
                                }}
                            </div>

                            <span class="text-sm font-semibold text-slate-700">
                                {{ campus.name }}
                            </span>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <span class="rounded bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600">
                            {{ campus.code }}
                        </span>
                    </td>

                    <td class="max-w-xs px-6 py-4">
                        <span class="line-clamp-1 text-sm text-slate-400">
                            {{
                                campus.address || '—'
                            }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex flex-col text-xs text-slate-500">
                            <span>
                                Lat:
                                {{
                                    campus.latitude ??
                                    '-'
                                }}
                            </span>

                            <span>
                                Lng:
                                {{
                                    campus.longitude ??
                                    '-'
                                }}
                            </span>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <span v-if="campus.is_active"
                            class="inline-flex items-center gap-2 rounded-full bg-emerald-50/80 px-3 py-1.5 text-xs font-medium text-emerald-600 ring-1 ring-emerald-100">
                            <span class="relative flex h-1.5 w-1.5">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-60" />
                                <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-emerald-400" />
                            </span>
                            Activo
                        </span>

                        <span v-else
                            class="inline-flex items-center gap-2 rounded-full bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-400 ring-1 ring-slate-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-slate-300" />
                            Inactivo
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-sm text-slate-600">
                                {{
                                    formatDate(
                                        campus.created_at
                                    ).date
                                }}
                            </span>

                            <span class="text-xs text-slate-400">
                                {{
                                    formatDate(
                                        campus.created_at
                                    ).time
                                }}
                            </span>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <Button variant="ghost" size="icon"
                                class="h-8 w-8 rounded-lg text-slate-400 transition-all duration-200 hover:scale-105 hover:bg-slate-100 hover:text-slate-600 active:scale-95"
                                @click="emit('edit', campus)">
                                <Pencil class="h-3.5 w-3.5" />
                                <span class="sr-only">Editar</span>
                            </Button>

                            <Button variant="ghost" size="icon"
                                class="h-8 w-8 rounded-lg text-slate-400 transition-all duration-200 hover:scale-105 hover:bg-red-50 hover:text-red-400 active:scale-95"
                                @click="emit('delete', campus)">
                                <Trash2 class="h-3.5 w-3.5" />
                                <span class="sr-only">Eliminar</span>
                            </Button>
                        </div>
                    </td>
                </tr>

                <tr v-if="campuses.length === 0">
                    <td colspan="8" class="py-20 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50 ring-1 ring-slate-100">
                                <Building2 class="h-5 w-5 text-slate-300" />
                            </div>

                            <div>
                                <p class="text-sm font-medium text-slate-500">
                                    Sin campus
                                </p>

                                <p class="mt-0.5 text-xs text-slate-400">
                                    No existen campus registrados.
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>