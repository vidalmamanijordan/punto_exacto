<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import DashboardTabs from '@/modules/dashboard/components/DashboardTabs.vue';
import DashboardFilters from '@/modules/dashboard/widgets/DashboardFilters.vue';
import DashboardSummary from '@/modules/dashboard/sections/DashboardSummary.vue';
import DashboardReports from '@/modules/dashboard/sections/DashboardReports.vue';
import DashboardRankings from '@/modules/dashboard/sections/DashboardRankings.vue';
import DashboardCampus from '@/modules/dashboard/sections/DashboardCampus.vue';
import {
    dashboardService,
    type DashboardFilters as DashboardFiltersType,
} from '@/modules/dashboard/services/dashboard.service';

import type { DashboardData } from '@/modules/dashboard/types/dashboard';
import type { DashboardTab } from '@/modules/dashboard/components/DashboardTabs.vue';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: '/dashboard',
            },
        ],
    },
});

/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/

const loading = ref(true);

const dashboard = ref<DashboardData | null>(null);

const currentTab = ref<DashboardTab>('summary');

const filters = ref<DashboardFiltersType>({
    period: '30',
    campus: 'all',
    category: 'all',
});

/*
|--------------------------------------------------------------------------
| Load Dashboard
|--------------------------------------------------------------------------
*/

const loadDashboard = async () => {

    loading.value = true;

    try {

        dashboard.value = await dashboardService.getDashboard(
            filters.value,
        );

    } catch (error) {

        console.error('Error cargando Dashboard:', error);

    } finally {

        loading.value = false;

    }

};

onMounted(loadDashboard);
</script>

<template>

    <Head title="Dashboard" />

    <div class="space-y-6 p-6">

        <!-- Header -->

        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Dashboard
            </h1>

            <p class="mt-1 text-slate-500">
                Panel analítico de la plataforma Punto Exacto.
            </p>
        </div>

        <!-- Filtros -->

        <DashboardFilters v-model="filters" :campuses="dashboard?.filters.campuses ?? []"
            :categories="dashboard?.filters.categories ?? []" :periods="dashboard?.filters.periods ?? []"
            :loading="loading" @refresh="loadDashboard" />

        <!-- Tabs -->

        <DashboardTabs v-model="currentTab" />

        <!-- Loading -->

        <div v-if="loading" class="rounded-xl border bg-white p-16 text-center text-slate-500">
            Cargando información...
        </div>

        <!-- Dashboard -->

        <template v-else-if="dashboard">

            <DashboardSummary v-if="currentTab === 'summary'" :dashboard="dashboard" />

            <DashboardReports v-else-if="currentTab === 'reports'" :dashboard="dashboard" />

            <DashboardRankings v-else-if="currentTab === 'rankings'" :dashboard="dashboard" />

            <DashboardCampus v-else-if="currentTab === 'campus'" :dashboard="dashboard" />

        </template>

    </div>
</template>