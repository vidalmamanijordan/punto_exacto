<script setup lang="ts">
import {
    Pencil,
    Trash2,
    Star,
} from '@lucide/vue';

import { Button } from '@/components/ui/button';
import type { Rating } from '../types/rating';

defineProps<{
    ratings: Rating[];
    loading?: boolean;
}>();

const emit = defineEmits<{
    edit: [Rating];
    delete: [Rating];
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
                        Lugar
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest text-slate-400">
                        Calificación
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest text-slate-400">
                        Comentario
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest text-slate-400">
                        Fecha
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
                                Cargando valoraciones...
                            </p>

                        </div>

                    </td>
                </tr>

                <!-- Sin registros -->

                <tr v-else-if="ratings.length === 0">

                    <td colspan="7" class="py-20 text-center">

                        <div class="flex flex-col items-center gap-3">

                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50 ring-1 ring-slate-100">
                                <Star class="h-5 w-5 text-slate-300" />
                            </div>

                            <div>

                                <p class="text-sm font-medium text-slate-500">
                                    Sin valoraciones registradas
                                </p>

                                <p class="mt-0.5 text-xs text-slate-400">
                                    Todavía no existen valoraciones.
                                </p>

                            </div>

                        </div>

                    </td>

                </tr>

                <!-- Datos -->

                <tr v-for="(rating, index) in ratings" :key="rating.id"
                    class="group border-l-2 border-l-transparent transition-all duration-200 hover:border-l-slate-300 hover:bg-slate-50/60">

                    <td class="px-5 py-4 text-center">
                        <span class="font-mono text-xs text-slate-300">
                            {{ String(index + 1).padStart(2, '0') }}
                        </span>
                    </td>

                    <!-- Usuario -->

                    <td class="px-6 py-4">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-xs font-semibold text-slate-500">
                                {{ rating.user.name.charAt(0).toUpperCase() }}
                            </div>

                            <div>

                                <p class="text-sm font-semibold text-slate-700">
                                    {{ rating.user.name }}
                                </p>

                                <p class="text-xs text-slate-400">
                                    {{ rating.user.email }}
                                </p>

                            </div>

                        </div>

                    </td>

                    <!-- Lugar -->

                    <td class="px-6 py-4">

                        <span class="text-sm font-medium text-slate-700">
                            {{ rating.place.name }}
                        </span>

                    </td>

                    <!-- Estrellas -->

                    <td class="px-6 py-4">

                        <div class="flex items-center gap-1">

                            <Star v-for="star in 5" :key="star" class="h-4 w-4" :class="star <= rating.rating
                                ? 'fill-yellow-400 text-yellow-400'
                                : 'text-slate-200'" />

                            <span class="ml-2 text-xs text-slate-500">
                                {{ rating.rating }}/5
                            </span>

                        </div>

                    </td>

                    <!-- Comentario -->

                    <td class="max-w-xs px-6 py-4">

                        <span class="line-clamp-2 text-sm text-slate-500">

                            {{ rating.comment || '—' }}

                        </span>

                    </td>

                    <!-- Fecha -->

                    <td class="px-6 py-4">

                        <div class="flex flex-col">

                            <span class="text-sm text-slate-600">
                                {{ formatDate(rating.created_at).date }}
                            </span>

                            <span class="text-xs text-slate-400">
                                {{ formatDate(rating.created_at).time }}
                            </span>

                        </div>

                    </td>

                    <!-- Acciones -->

                    <td class="px-6 py-4">

                        <div class="flex items-center justify-center gap-1.5">

                            <Button variant="ghost" size="icon"
                                class="h-8 w-8 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600"
                                @click="emit('edit', rating)">
                                <Pencil class="h-3.5 w-3.5" />
                            </Button>

                            <Button variant="ghost" size="icon"
                                class="h-8 w-8 rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-500"
                                @click="emit('delete', rating)">
                                <Trash2 class="h-3.5 w-3.5" />
                            </Button>

                        </div>

                    </td>

                </tr>

            </tbody>

        </table>
    </div>
</template>
