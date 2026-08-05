<script setup lang="ts">
import { Star } from '@lucide/vue';
import type {
    DashboardRating,
} from '../types/dashboard';

defineProps<{
    ratings: DashboardRating[];
}>();

const formatDate = (date: string) => {
    return new Date(date).toLocaleString('es-PE', {
        dateStyle: 'short',
        timeStyle: 'short',
    });
};
</script>

<template>
    <div class="rounded-xl border bg-white shadow-sm">
        <!-- Header -->
        <div class="border-b px-6 py-4">
            <div class="flex items-center gap-3">
                <Star class="h-5 w-5 text-amber-500" />
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">
                        Últimas valoraciones
                    </h2>
                    <p class="text-sm text-slate-500">
                        Valoraciones más recientes realizadas por los usuarios.
                    </p>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">
                            Usuario
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">
                            Lugar
                        </th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-slate-600">
                            Estrellas
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">
                            Comentario
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">
                            Fecha
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="rating in ratings" :key="rating.id" class="border-t hover:bg-slate-50">
                        <td class="px-6 py-4">
                            {{ rating.user.name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ rating.place.name }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center">
                                <span class="rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-700">
                                    {{ rating.rating }} ★
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ rating.comment || 'Sin comentario' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">
                            {{ formatDate(rating.created_at) }}
                        </td>
                    </tr>
                    <tr v-if="ratings.length === 0">
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                            No existen valoraciones recientes.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
