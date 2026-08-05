<script setup lang="ts">
import DashboardSection from '../widgets/DashboardSection.vue';

import DashboardSearchChart from '../charts/DashboardSearchChart.vue';
import DashboardFavoritesChart from '../charts/DashboardFavoritesChart.vue';
import DashboardRatingsChart from '../charts/DashboardRatingsChart.vue';
import DashboardUsersChart from '../charts/DashboardUsersChart.vue';

import type { DashboardData } from '../types/dashboard';

const props = defineProps<{
    dashboard: DashboardData;
}>();
</script>

<template>
    <div class="space-y-6">

        <DashboardSection title="Reportes Estadísticos"
            description="Visualiza el comportamiento de la plataforma mediante gráficos analíticos en tiempo real.">

            <div class="grid gap-6 lg:grid-cols-2">

                <!-- Búsquedas -->
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-5">
                        <h3 class="text-lg font-semibold text-slate-800">
                            Búsquedas por día
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Cantidad de búsquedas realizadas diariamente por los usuarios.
                        </p>
                    </div>

                    <DashboardSearchChart :data="props.dashboard.reports.search_history" />
                </div>

                <!-- Favoritos -->
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-5">
                        <h3 class="text-lg font-semibold text-slate-800">
                            Favoritos por día
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Lugares agregados a favoritos diariamente.
                        </p>
                    </div>

                    <DashboardFavoritesChart :data="props.dashboard.reports.favorites" />
                </div>

                <!-- Valoraciones -->
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-5">
                        <h3 class="text-lg font-semibold text-slate-800">
                            Valoraciones por día
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Número de valoraciones registradas diariamente.
                        </p>
                    </div>

                    <DashboardRatingsChart :data="props.dashboard.reports.ratings" />
                </div>

                <!-- Usuarios -->
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-5">
                        <h3 class="text-lg font-semibold text-slate-800">
                            Usuarios registrados
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Evolución del crecimiento de usuarios registrados.
                        </p>
                    </div>

                    <DashboardUsersChart :data="props.dashboard.reports.users" />
                </div>

            </div>

        </DashboardSection>

    </div>
</template>