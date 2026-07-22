<script setup lang="ts">
import { Search, Pencil, Trash2 } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import type { SearchHistory } from '../types/search-history';

defineProps<{
    searchHistories: SearchHistory[];
}>();

const emit = defineEmits<{
    edit: [searchHistory: SearchHistory];
    delete: [searchHistory: SearchHistory];
}>();

function formatDate(dateString: string): { date: string; time: string } {
    const d = new Date(dateString);
    return {
        date: d.toLocaleDateString('es-PE', { day: '2-digit', month: 'short', year: 'numeric' }),
        time: d.toLocaleTimeString('es-PE', { hour: '2-digit', minute: '2-digit' }),
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
                        Texto buscado
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
                <tr
                    v-for="(history, index) in searchHistories"
                    :key="history.id"
                    class="group border-l-2 border-l-transparent transition-all duration-200 hover:border-l-slate-300 hover:bg-slate-50/60"
                >
                    <td class="px-5 py-4 text-center">
                        <span class="font-mono text-xs text-slate-300">
                            {{ String(index + 1).padStart(2, '0') }}
                        </span>
                    </td>

                    <!-- Usuario -->
                    <td class="px-6 py-4">
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                            {{ history.user?.name ?? '-' }}
                        </span>
                    </td>

                    <!-- Lugar -->
                    <td class="px-6 py-4">
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                            {{ history.place?.name ?? '-' }}
                        </span>
                    </td>

                    <!-- Texto buscado -->
                    <td class="max-w-sm px-6 py-4">
                        <span class="line-clamp-2 text-sm font-medium text-slate-700">
                            {{ history.search_text }}
                        </span>
                    </td>

                    <!-- Fecha -->
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-sm text-slate-600">
                                {{ formatDate(history.created_at).date }}
                            </span>
                            <span class="text-xs text-slate-400">
                                {{ formatDate(history.created_at).time }}
                            </span>
                        </div>
                    </td>

                    <!-- Acciones -->
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <Button
                                variant="ghost"
                                size="icon"
                                class="h-8 w-8 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600"
                                @click="emit('edit', history)"
                            >
                                <Pencil class="h-3.5 w-3.5" />
                            </Button>

                            <Button
                                variant="ghost"
                                size="icon"
                                class="h-8 w-8 rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-500"
                                @click="emit('delete', history)"
                            >
                                <Trash2 class="h-3.5 w-3.5" />
                            </Button>
                        </div>
                    </td>
                </tr>

                <tr v-if="searchHistories.length === 0">
                    <td colspan="6" class="py-20 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50 ring-1 ring-slate-100">
                                <Search class="h-5 w-5 text-slate-300" />
                            </div>

                            <div>
                                <p class="text-sm font-medium text-slate-500">
                                    Sin historiales registrados
                                </p>

                                <p class="mt-0.5 text-xs text-slate-400">
                                    No existen historiales de búsqueda registrados aún.
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>

        </table>
    </div>
</template>
