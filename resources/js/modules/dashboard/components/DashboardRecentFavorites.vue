<script setup lang="ts">
import { Heart } from '@lucide/vue';
import type {
    DashboardFavorite,
} from '../types/dashboard';

defineProps<{
    favorites: DashboardFavorite[];
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
                <Heart class="h-5 w-5 text-red-500" />
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">
                        Últimos favoritos
                    </h2>
                    <p class="text-sm text-slate-500">
                        Lugares agregados recientemente a favoritos.
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
                            Fecha
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="favorite in favorites" :key="favorite.id" class="border-t hover:bg-slate-50">
                        <td class="px-6 py-4">
                            {{ favorite.user.name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ favorite.place.name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">
                            {{ formatDate(favorite.created_at) }}
                        </td>
                    </tr>
                    <tr v-if="favorites.length === 0">
                        <td colspan="3" class="px-6 py-10 text-center text-slate-400">
                            No existen favoritos recientes.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
