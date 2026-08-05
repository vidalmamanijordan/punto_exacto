<script setup lang="ts">
import { computed } from 'vue';
import {
    Building2,
    MapPinned,
    Search,
    Heart,
    Star,
} from '@lucide/vue';

import DashboardSection from '../widgets/DashboardSection.vue';
import DashboardMetric from '../widgets/DashboardMetric.vue';

import type {
    DashboardData,
} from '../types/dashboard';

const props = defineProps<{
    dashboard: DashboardData;
}>();

const campuses = computed(() => props.dashboard.campus.statistics);
</script>

<template>
    <div class="space-y-6">

        <DashboardSection title="Estadísticas por Campus"
            description="Consulta el comportamiento y la actividad de cada campus dentro de Punto Exacto.">

            <div class="grid gap-6 xl:grid-cols-3">

                <div v-for="campus in campuses" :key="campus.id"
                    class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

                    <!-- Header -->

                    <div class="mb-6 flex items-center gap-4">

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100">
                            <Building2 class="h-6 w-6 text-blue-600" />
                        </div>

                        <div>

                            <h3 class="text-lg font-semibold text-slate-800">
                                {{ campus.name }}
                            </h3>

                            <p class="text-sm text-slate-500">
                                {{ campus.code }}
                            </p>

                        </div>

                    </div>

                    <!-- Métricas -->

                    <div class="grid grid-cols-2 gap-4">

                        <DashboardMetric label="Lugares" :value="campus.places" :icon="MapPinned"
                            color="bg-green-100" />

                        <DashboardMetric label="Búsquedas" :value="campus.searches" :icon="Search"
                            color="bg-blue-100" />

                        <DashboardMetric label="Favoritos" :value="campus.favorites" :icon="Heart" color="bg-red-100" />

                        <DashboardMetric label="Valoraciones" :value="campus.ratings" :icon="Star"
                            color="bg-amber-100" />

                    </div>

                </div>

            </div>

        </DashboardSection>

        <!-- Mapa -->

        <DashboardSection title="Mapa General"
            description="En futuras versiones se mostrará la ubicación geográfica de todos los campus.">

            <div
                class="flex h-96 items-center justify-center rounded-xl border border-dashed border-slate-300 bg-slate-50 text-slate-400">
                Google Maps será integrado aquí.
            </div>

        </DashboardSection>

    </div>
</template>