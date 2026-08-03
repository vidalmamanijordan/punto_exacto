<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import DashboardTabs from '@/modules/dashboard/components/DashboardTabs.vue';
import DashboardSummary from '@/modules/dashboard/components/DashboardSummary.vue';
import DashboardReports from '@/modules/dashboard/components/DashboardReports.vue';
import DashboardRankings from '@/modules/dashboard/components/DashboardRankings.vue';
import DashboardCampus from '@/modules/dashboard/components/DashboardCampus.vue';
import { dashboardService } from '@/modules/dashboard/services/dashboard.service';
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

const loading = ref(false);
const dashboard = ref<DashboardData | null>(null);
const currentTab = ref<DashboardTab>('summary');

const loadDashboard = async () => {
    try {
        loading.value = true;
        dashboard.value = await dashboardService.getDashboard();
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
};

onMounted(loadDashboard);
</script>

<template>

    <Head title="Dashboard" />
    <div class="space-y-6 p-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Dashboard
            </h1>
            <p class="mt-1 text-slate-500">
                Resumen analítico de Punto Exacto.
            </p>
        </div>
        <DashboardTabs v-model="currentTab" />
        <div v-if="loading" class="rounded-xl border bg-white p-10 text-center text-slate-500">
            Cargando información...
        </div>
        <template v-else-if="dashboard">
            <DashboardSummary v-if="currentTab === 'summary'" :dashboard="dashboard" />
            <DashboardReports v-else-if="currentTab === 'reports'" :dashboard="dashboard" />
            <DashboardRankings v-else-if="currentTab === 'rankings'" :dashboard="dashboard" />
            <DashboardCampus v-else-if="currentTab === 'campus'" :dashboard="dashboard" />
        </template>
    </div>
</template>
