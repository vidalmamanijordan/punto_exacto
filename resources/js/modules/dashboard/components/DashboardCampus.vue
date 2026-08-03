<script setup lang="ts">
import { computed } from 'vue';
import { Building2 } from '@lucide/vue';
import type {
    DashboardData,
} from '../types/dashboard';

const props = defineProps<{
    dashboard: DashboardData;
}>();

const campuses = computed(() => props.dashboard.campus_statistics);
</script>

<template>
    <div class="space-y-6">
        <!-- Encabezado -->
        <div class="rounded-xl border bg-white p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-800">
                Estadísticas por Campus
            </h2>
            <p class="mt-2 text-sm text-slate-500">
                Consulta el comportamiento de cada campus de manera individual.
            </p>
        </div>
        <!-- Tarjetas -->
        <div class="grid gap-6 lg:grid-cols-3">
            <div v-for="campus in campuses" :key="campus.id" class="rounded-xl border bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100">
                        <Building2 class="h-5 w-5 text-blue-600" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">
                            {{ campus.name }}
                        </h3>
                        <span class="text-xs text-slate-500">
                            {{ campus.code }}
                        </span>
                    </div>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">
                            Lugares
                        </span>
                        <strong>
                            {{ campus.places }}
                        </strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">
                            Búsquedas
                        </span>
                        <strong>
                            {{ campus.searches }}
                        </strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">
                            Favoritos
                        </span>
                        <strong>
                            {{ campus.favorites }}
                        </strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">
                            Valoraciones
                        </span>
                        <strong>
                            {{ campus.ratings }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
        <!-- Google Maps -->
        <div class="rounded-xl border bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-semibold">
                Mapa General del Campus
            </h3>
            <div class="flex h-96 items-center justify-center rounded-lg border border-dashed text-slate-400">
                Google Maps será integrado aquí.
            </div>
        </div>
    </div>
</template>