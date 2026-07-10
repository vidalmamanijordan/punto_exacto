
<script setup lang="ts">
import { MapPin, Pencil, Trash2 } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import type { Place } from '../types/place';

defineProps<{
    places: Place[];
}>();

const emit = defineEmits<{
    edit: [Place];
    delete: [Place];
}>();

function formatDate(dateString: string): {
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
    <div
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
    >
        <table class="min-w-full">

            <thead>
                <tr
                    class="border-b border-slate-100 bg-slate-50/80"
                >
                    <th
                        class="w-12 px-5 py-4 text-center text-xs font-medium tracking-widest text-slate-400 uppercase"
                    >
                        #
                    </th>

                    <th
                        class="px-6 py-4 text-left text-xs font-medium tracking-widest text-slate-400 uppercase"
                    >
                        Lugar
                    </th>

                    <th
                        class="px-6 py-4 text-left text-xs font-medium tracking-widest text-slate-400 uppercase"
                    >
                        Campus
                    </th>

                    <th
                        class="px-6 py-4 text-left text-xs font-medium tracking-widest text-slate-400 uppercase"
                    >
                        Categoría
                    </th>

                    <th
                        class="px-6 py-4 text-left text-xs font-medium tracking-widest text-slate-400 uppercase"
                    >
                        Ubicación
                    </th>

                    <th
                        class="px-6 py-4 text-left text-xs font-medium tracking-widest text-slate-400 uppercase"
                    >
                        Estado
                    </th>

                    <th
                        class="px-6 py-4 text-left text-xs font-medium tracking-widest text-slate-400 uppercase"
                    >
                        Creado
                    </th>

                    <th
                        class="px-6 py-4 text-center text-xs font-medium tracking-widest text-slate-400 uppercase"
                    >
                        Acciones
                    </th>
                </tr>
            </thead>

            <tbody
                class="divide-y divide-slate-100"
            >
                <tr
                    v-for="(place, index) in places"
                    :key="place.id"
                    class="group border-l-2 border-l-transparent transition-all duration-200 hover:border-l-slate-300 hover:bg-slate-50/60"
                >
                    <td class="px-5 py-4 text-center">
                        <span
                            class="font-mono text-xs text-slate-300"
                        >
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

                    <!-- Lugar -->
                    <td class="px-6 py-4">
                        <div
                            class="flex items-center gap-3"
                        >
                            <div
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-xs font-semibold text-slate-500"
                            >
                                {{
                                    place.name
                                        .charAt(0)
                                        .toUpperCase()
                                }}
                            </div>

                            <div
                                class="flex flex-col"
                            >
                                <span
                                    class="text-sm font-semibold text-slate-700"
                                >
                                    {{
                                        place.name
                                    }}
                                </span>

                                <span
                                    class="text-xs text-slate-400"
                                >
                                    {{
                                        place.room ||
                                        'Sin ambiente'
                                    }}
                                </span>
                            </div>
                        </div>
                    </td>

                    <!-- Campus -->
                    <td class="px-6 py-4">
                        <span
                            class="text-sm text-slate-600"
                        >
                            {{
                                place.campus
                                    ?.name
                            }}
                        </span>
                    </td>

                    <!-- Categoría -->
                    <td class="px-6 py-4">
                        <span
                            class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-600"
                        >
                            {{
                                place.category
                                    ?.name
                            }}
                        </span>
                    </td>

                    <!-- Ubicación -->
                    <td class="px-6 py-4">
                        <div
                            class="flex flex-col"
                        >
                            <span
                                class="text-sm text-slate-600"
                            >
                                {{
                                    place.building ||
                                    'Sin edificio'
                                }}
                            </span>

                            <span
                                class="text-xs text-slate-400"
                            >
                                Piso:
                                {{
                                    place.floor ||
                                    '-'
                                }}
                            </span>
                        </div>
                    </td>

                    <!-- Estado -->
                    <td class="px-6 py-4">
                        <span
                            v-if="
                                place.is_active
                            "
                            class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-600"
                        >
                            Activo
                        </span>

                        <span
                            v-else
                            class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-500"
                        >
                            Inactivo
                        </span>
                    </td>

                    <!-- Fecha -->
                    <td class="px-6 py-4">
                        <div
                            class="flex flex-col"
                        >
                            <span
                                class="text-sm text-slate-600"
                            >
                                {{
                                    formatDate(
                                        place.created_at
                                    ).date
                                }}
                            </span>

                            <span
                                class="text-xs text-slate-400"
                            >
                                {{
                                    formatDate(
                                        place.created_at
                                    ).time
                                }}
                            </span>
                        </div>
                    </td>

                    <!-- Acciones -->
                    <td class="px-6 py-4">
                        <div
                            class="flex items-center justify-center gap-1.5"
                        >
                            <Button
                                @click="
                                    emit(
                                        'edit',
                                        place
                                    )
                                "
                                variant="ghost"
                                size="icon"
                                class="h-8 w-8 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600"
                            >
                                <Pencil
                                    class="h-3.5 w-3.5"
                                />
                            </Button>

                            <Button
                                @click="
                                    emit(
                                        'delete',
                                        place
                                    )
                                "
                                variant="ghost"
                                size="icon"
                                class="h-8 w-8 rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-500"
                            >
                                <Trash2
                                    class="h-3.5 w-3.5"
                                />
                            </Button>
                        </div>
                    </td>
                </tr>

                <tr v-if="places.length === 0">
                    <td
                        colspan="8"
                        class="py-20 text-center"
                    >
                        <div
                            class="flex flex-col items-center gap-3"
                        >
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50 ring-1 ring-slate-100"
                            >
                                <MapPin
                                    class="h-5 w-5 text-slate-300"
                                />
                            </div>

                            <div>
                                <p
                                    class="text-sm font-medium text-slate-500"
                                >
                                    Sin lugares
                                </p>

                                <p
                                    class="mt-0.5 text-xs text-slate-400"
                                >
                                    No existen lugares registrados aún.
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>

            </tbody>

        </table>
    </div>
</template>