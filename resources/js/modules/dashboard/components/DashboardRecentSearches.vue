<script setup lang="ts">
import { Search } from '@lucide/vue';

import type {
    DashboardSearchHistory,
} from '../types/dashboard';

defineProps<{
    searchHistories: DashboardSearchHistory[];
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
                <Search class="h-5 w-5 text-slate-500" />
                <div>

                    <h2 class="text-lg font-semibold text-slate-800">
                        Últimas búsquedas
                    </h2>

                    <p class="text-sm text-slate-500">
                        Historial reciente de consultas realizadas por los usuarios.
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
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">
                            Búsqueda
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">
                            Fecha
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="history in searchHistories" :key="history.id" class="border-t hover:bg-slate-50">
                        <td class="px-6 py-4">
                            {{ history.user.name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ history.place.name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ history.search_text }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">
                            {{ formatDate(history.created_at) }}
                        </td>
                    </tr>
                    <tr v-if="searchHistories.length === 0">
                        <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                            No existen búsquedas recientes.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
