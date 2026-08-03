<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import {
    Building2,
    FolderTree,
    MapPinned,
    CircleHelp,
    Users,
    Star,
    Heart,
    Search,
} from '@lucide/vue';
import StatCard from '@/modules/dashboard/components/StatCard.vue';
import DashboardRecentSearches from '@/modules/dashboard/components/DashboardRecentSearches.vue';
import DashboardRecentRatings from '@/modules/dashboard/components/DashboardRecentRatings.vue';
import DashboardRecentFavorites from '@/modules/dashboard/components/DashboardRecentFavorites.vue';
import { dashboardService } from '@/modules/dashboard/services/dashboard.service';
import type {
    DashboardData,
} from '@/modules/dashboard/types/dashboard';

const loading = ref(false);
const dashboard = ref<DashboardData | null>(null);
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
    <AppLayout>
        <div class="space-y-6 p-6">

            <!-- Header -->
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    Dashboard
                </h1>
                <p class="mt-1 text-slate-500">
                    Resumen general de la plataforma Punto Exacto.
                </p>
            </div>

            <!-- Loading -->
            <div v-if="loading" class="rounded-xl border bg-white p-10 text-center text-slate-500">
                Cargando información...
            </div>
            <template v-else-if="dashboard">

                <!-- Tarjetas -->
                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
                    <StatCard title="Campus" :value="dashboard.statistics.total_campuses" :icon="Building2" />
                    <StatCard title="Categorías" :value="dashboard.statistics.total_categories" :icon="FolderTree" />
                    <StatCard title="Lugares" :value="dashboard.statistics.total_places" :icon="MapPinned" />
                    <StatCard title="FAQs" :value="dashboard.statistics.total_faqs" :icon="CircleHelp" />
                    <StatCard title="Usuarios" :value="dashboard.statistics.total_users" :icon="Users" />
                    <StatCard title="Valoraciones" :value="dashboard.statistics.total_ratings" :icon="Star" />
                    <StatCard title="Favoritos" :value="dashboard.statistics.total_favorites" :icon="Heart" />
                    <StatCard title="Búsquedas" :value="dashboard.statistics.total_search_histories" :icon="Search" />
                </div>

                <!-- Tablas -->
                <div class="grid gap-6">
                    <DashboardRecentSearches :search-histories="dashboard.recent_search_histories" />
                    <DashboardRecentRatings :ratings="dashboard.recent_ratings" />
                    <DashboardRecentFavorites :favorites="dashboard.recent_favorites" />
                </div>
            </template>
        </div>
    </AppLayout>
</template>
