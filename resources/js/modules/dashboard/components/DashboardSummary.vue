<script setup lang="ts">
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
import DashboardSection from './DashboardSection.vue';
import DashboardMetric from './DashboardMetric.vue';
import DashboardRecentSearches from './DashboardRecentSearches.vue';
import DashboardRecentRatings from './DashboardRecentRatings.vue';
import DashboardRecentFavorites from './DashboardRecentFavorites.vue';
import type { DashboardData } from '../types/dashboard';

const props = defineProps<{
    dashboard: DashboardData;
}>();
</script>

<template>
    <div class="space-y-6">
        <!-- Estadísticas generales -->
        <DashboardSection
            title="Estadísticas generales"
            description="Resumen del estado actual de la plataforma."
        >
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <DashboardMetric
                    label="Campus"
                    :value="props.dashboard.statistics.total_campuses"
                    :icon="Building2"
                    color="bg-blue-100"
                />
                <DashboardMetric
                    label="Categorías"
                    :value="props.dashboard.statistics.total_categories"
                    :icon="Layers"
                    color="bg-purple-100"
                />
                <DashboardMetric
                    label="Lugares"
                    :value="props.dashboard.statistics.total_places"
                    :icon="MapPin"
                    color="bg-green-100"
                />
                <DashboardMetric
                    label="FAQs"
                    :value="props.dashboard.statistics.total_faqs"
                    :icon="HelpCircle"
                    color="bg-yellow-100"
                />
                <DashboardMetric
                    label="Usuarios"
                    :value="props.dashboard.statistics.total_users"
                    :icon="Users"
                    color="bg-sky-100"
                />
                <DashboardMetric
                    label="Valoraciones"
                    :value="props.dashboard.statistics.total_ratings"
                    :icon="Star"
                    color="bg-amber-100"
                />
                <DashboardMetric
                    label="Favoritos"
                    :value="props.dashboard.statistics.total_favorites"
                    :icon="Heart"
                    color="bg-red-100"
                />
                <DashboardMetric
                    label="Búsquedas"
                    :value="props.dashboard.statistics.total_search_histories"
                    :icon="Search"
                    color="bg-slate-100"
                />
            </div>
        </DashboardSection>

        <!-- Actividad reciente -->
        <DashboardRecentSearches :search-histories="props.dashboard.recent_search_histories" />
        <DashboardRecentRatings :ratings="props.dashboard.recent_ratings" />
        <DashboardRecentFavorites :favorites="props.dashboard.recent_favorites" />
    </div>
</template>
