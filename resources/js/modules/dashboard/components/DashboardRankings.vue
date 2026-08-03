<script setup lang="ts">
import { computed } from 'vue';
import DashboardTopPlaces from './DashboardTopPlaces.vue';
import type {
    DashboardData,
} from '../types/dashboard';

const props = defineProps<{
    dashboard: DashboardData;
}>();

const topSearchedPlaces = computed(() =>
    props.dashboard.top_searched_places.map(item => ({
        id: item.place.id,
        name: item.place.name,
        total: item.total,
    }))
);

const topFavoritePlaces = computed(() =>
    props.dashboard.top_favorite_places.map(item => ({
        id: item.place.id,
        name: item.place.name,
        total: item.total,
    }))
);

const topRatedPlaces = computed(() =>
    props.dashboard.top_rated_places.map(item => ({
        id: item.place.id,
        name: item.place.name,
        total: item.average_rating,
    }))
);
</script>

<template>
    <div class="space-y-6">
        <!-- Encabezado -->
        <div class="rounded-xl border bg-white p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-800">
                Rankings del Sistema
            </h2>
            <p class="mt-2 text-sm text-slate-500">
                Consulta los lugares con mayor actividad dentro de la plataforma.
            </p>
        </div>
        <!-- Rankings -->
        <div class="grid gap-6 xl:grid-cols-3">
            <DashboardTopPlaces title="Lugares más buscados" :places="topSearchedPlaces" />
            <DashboardTopPlaces title="Lugares favoritos" :places="topFavoritePlaces" />
            <DashboardTopPlaces title="Mejor valorados" :places="topRatedPlaces" />
        </div>
    </div>
</template>