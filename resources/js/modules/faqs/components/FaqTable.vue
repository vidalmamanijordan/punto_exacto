<script setup lang="ts">
import {
    CircleHelp,
    Pencil,
    Trash2,
} from '@lucide/vue';
import { Button } from '@/components/ui/button';
import type { Faq } from '../types/faq';

defineProps<{
    faqs: Faq[];
}>();

const emit = defineEmits<{
    edit: [Faq];
    delete: [Faq];
}>();

function formatDate(
    dateString: string
): {
    date: string;
    time: string;
} {
    const d = new Date(dateString);
    return {
        date: d.toLocaleDateString(
            'es-PE',
            {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
            }
        ),
        time: d.toLocaleTimeString(
            'es-PE',
            {
                hour: '2-digit',
                minute: '2-digit',
            }
        ),
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
                        Categoría
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest text-slate-400">
                        Pregunta
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest text-slate-400">
                        Respuesta
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
                <tr v-for="(faq, index) in faqs" :key="faq.id"
                    class="group border-l-2 border-l-transparent transition-all duration-200 hover:border-l-slate-300 hover:bg-slate-50/60">
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

                    <!-- Categoría -->
                    <td class="px-6 py-4">
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                            {{
                                faq.category
                                    ?.name ??
                                '-'
                            }}
                        </span>
                    </td>

                    <!-- Pregunta -->
                    <td class="max-w-sm px-6 py-4">
                        <span class="line-clamp-2 text-sm font-medium text-slate-700">
                            {{
                                faq.question
                            }}
                        </span>
                    </td>

                    <!-- Respuesta -->
                    <td class="max-w-md px-6 py-4">
                        <span class="line-clamp-2 text-sm text-slate-500">
                            {{
                                faq.answer
                            }}
                        </span>
                    </td>

                    <!-- Estado -->
                    <td class="px-6 py-4">
                        <span v-if="
                            faq.is_active
                        "
                            class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-600 ring-1 ring-emerald-100">
                            <span class="relative flex h-1.5 w-1.5">
                                <span
                                    class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-60" />

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

                    <!-- Fecha -->
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-sm text-slate-600">
                                {{
                                    formatDate(
                                        faq.created_at
                                    ).date
                                }}
                            </span>

                            <span class="text-xs text-slate-400">
                                {{
                                    formatDate(
                                        faq.created_at
                                    ).time
                                }}
                            </span>
                        </div>
                    </td>

                    <!-- Acciones -->
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <Button @click="
                                emit(
                                    'edit',
                                    faq
                                )
                                " variant="ghost" size="icon"
                                class="h-8 w-8 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                                <Pencil class="h-3.5 w-3.5" />
                            </Button>

                            <Button @click="
                                emit(
                                    'delete',
                                    faq
                                )
                                " variant="ghost" size="icon"
                                class="h-8 w-8 rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-500">
                                <Trash2 class="h-3.5 w-3.5" />
                            </Button>
                        </div>
                    </td>
                </tr>

                <tr v-if="faqs.length === 0">
                    <td colspan="7" class="py-20 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50 ring-1 ring-slate-100">
                                <CircleHelp class="h-5 w-5 text-slate-300" />
                            </div>

                            <div>
                                <p class="text-sm font-medium text-slate-500">
                                    Sin preguntas frecuentes
                                </p>

                                <p class="mt-0.5 text-xs text-slate-400">
                                    No existen FAQs registradas aún.
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>

            </tbody>

        </table>
    </div>
</template>
