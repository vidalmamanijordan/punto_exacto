<script setup lang="ts">
import { computed } from 'vue';
import {
    Building2,
    Layers,
    MapPin,
    HelpCircle,
    Users,
    Star,
    Heart,
    Search,
} from '@lucide/vue';
import DashboardSection from '../widgets/DashboardSection.vue';
import DashboardMetric from '../widgets/DashboardMetric.vue';
import DashboardRecentSearches from '../tables/DashboardRecentSearches.vue';
import DashboardRecentRatings from '../tables/DashboardRecentRatings.vue';
import DashboardRecentFavorites from '../tables/DashboardRecentFavorites.vue';
import type { DashboardData } from '../types/dashboard';

const props = defineProps<{
    dashboard: DashboardData;
}>();

const summary = computed(() => props.dashboard.summary);
</script>

<template>
    <div class="space-y-6">
        <!-- Estadísticas generales -->
        <DashboardSection title="Estadísticas generales"
            description="Resumen general del estado actual de la plataforma.">
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <DashboardMetric label="Campus" :value="summary.statistics.total_campuses" :icon="Building2"
                    color="bg-blue-100" />
                <DashboardMetric label="Categorías" :value="summary.statistics.total_categories" :icon="Layers"
                    color="bg-purple-100" />
                <DashboardMetric label="Lugares" :value="summary.statistics.total_places" :icon="MapPin"
                    color="bg-green-100" />
                <DashboardMetric label="FAQs" :value="summary.statistics.total_faqs" :icon="HelpCircle"
                    color="bg-yellow-100" />
                <DashboardMetric label="Usuarios" :value="summary.statistics.total_users" :icon="Users"
                    color="bg-sky-100" />
                <DashboardMetric label="Valoraciones" :value="summary.statistics.total_ratings" :icon="Star"
                    color="bg-amber-100" />
                <DashboardMetric label="Favoritos" :value="summary.statistics.total_favorites" :icon="Heart"
                    color="bg-red-100" />
                <DashboardMetric label="Búsquedas" :value="summary.statistics.total_search_histories" :icon="Search"
                    color="bg-slate-100" />
            </div>
        </DashboardSection>
        <!-- Actividad reciente -->
        <DashboardRecentSearches :search-histories="summary.recent_search_histories" />
        <DashboardRecentRatings :ratings="summary.recent_ratings" />
        <DashboardRecentFavorites :favorites="summary.recent_favorites" />
    </div>
</template>
