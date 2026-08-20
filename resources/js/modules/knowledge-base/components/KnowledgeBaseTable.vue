<script setup lang="ts">
import { Edit, FileText, Trash2 } from '@lucide/vue';
import type { KnowledgeBase } from '../types/knowledge-base';

withDefaults(defineProps<{
    items: KnowledgeBase[];
    loading?: boolean;
}>(), {
    items: () => [],
});

const emit = defineEmits<{
    edit: [item: KnowledgeBase];
    delete: [item: KnowledgeBase];
}>();

function truncateContent(content: string, length = 120): string {
    if (content.length <= length) {
        return content;
    }

    return `${content.substring(0, length)}...`;
}
</script>

<template>
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <!-- Loading -->
        <div v-if="loading" class="flex min-h-64 flex-col items-center justify-center px-6 py-12 text-center">
            <div class="mb-4 h-8 w-8 animate-spin rounded-full border-4 border-slate-200 border-t-blue-600"></div>

            <p class="text-sm font-medium text-slate-600">
                Cargando información...
            </p>

            <p class="mt-1 text-xs text-slate-400">
                Espere un momento.
            </p>
        </div>

        <!-- Empty -->
        <div v-else-if="items.length === 0"
            class="flex min-h-64 flex-col items-center justify-center px-6 py-12 text-center">
            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-slate-100">
                <FileText class="h-7 w-7 text-slate-400" />
            </div>

            <h3 class="text-base font-semibold text-slate-700">
                No hay información registrada
            </h3>

            <p class="mt-1 max-w-md text-sm text-slate-500">
                Todavía no se ha registrado información en la base de
                conocimiento.
            </p>

        </div>

        <!-- Table -->
        <div v-else class="overflow-x-auto">
            <table class="w-full min-w-[800px] text-left">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Título
                        </th>

                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Contenido
                        </th>

                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Estado
                        </th>

                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Acciones
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    <tr v-for="item in items" :key="item.id" class="transition hover:bg-slate-50">
                        <!-- Título -->
                        <td class="px-6 py-4 align-top">
                            <div class="max-w-xs">
                                <p class="font-medium text-slate-800" :title="item.title">
                                    {{ item.title }}
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    ID: {{ item.id }}
                                </p>
                            </div>
                        </td>

                        <!-- Contenido -->
                        <td class="px-6 py-4 align-top">
                            <p class="max-w-xl text-sm leading-6 text-slate-600" :title="item.content">
                                {{ truncateContent(item.content) }}
                            </p>
                        </td>

                        <!-- Estado -->
                        <td class="px-6 py-4 align-top">
                            <span v-if="item.is_active"
                                class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                                Activo
                            </span>

                            <span v-else
                                class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-slate-400"></span>

                                Inactivo
                            </span>
                        </td>

                        <!-- Acciones -->
                        <td class="px-6 py-4 align-top">
                            <div class="flex justify-end gap-2">
                                <button type="button"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                                    title="Editar" :disabled="loading" @click="emit('edit', item)">
                                    <Edit class="h-4 w-4" />
                                </button>

                                <button type="button"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1"
                                    title="Eliminar" :disabled="loading" @click="emit('delete', item)">
                                    <Trash2 class="h-4 w-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div v-if="!loading && items.length > 0" class="border-t border-slate-200 bg-slate-50 px-6 py-3">
            <p class="text-sm text-slate-500">
                Total de registros:
                <span class="font-semibold text-slate-700">
                    {{ items.length }}
                </span>
            </p>
        </div>
    </div>
</template>
