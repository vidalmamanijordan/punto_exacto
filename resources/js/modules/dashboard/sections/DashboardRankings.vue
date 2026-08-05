<script setup lang="ts">
import { computed } from 'vue';

import DashboardSection from '../widgets/DashboardSection.vue';
import DashboardTopPlaces from '../widgets/DashboardTopPlaces.vue';

import type {
    DashboardData,
} from '../types/dashboard';

const props = defineProps<{
    dashboard: DashboardData;
}>();

/*
|--------------------------------------------------------------------------
| Lugares más buscados
|--------------------------------------------------------------------------
*/

const topSearchedPlaces = computed(() =>
    props.dashboard.rankings.top_searched_places.map(item => ({
        id: item.place.id,
        name: item.place.name,
        total: item.total,
    }))
);

/*
|--------------------------------------------------------------------------
| Lugares favoritos
|--------------------------------------------------------------------------
*/

const topFavoritePlaces = computed(() =>
    props.dashboard.rankings.top_favorite_places.map(item => ({
        id: item.place.id,
        name: item.place.name,
        total: item.total,
    }))
);

/*
|--------------------------------------------------------------------------
| Lugares mejor valorados
|--------------------------------------------------------------------------
*/

const topRatedPlaces = computed(() =>
    props.dashboard.rankings.top_rated_places.map(item => ({
        id: item.place.id,
        name: item.place.name,
        total: item.average_rating,
    }))
);
</script>

<template>
    <div class="space-y-6">

        <DashboardSection title="Rankings del Sistema"
            description="Consulta los lugares con mayor interacción dentro de Punto Exacto.">

            <div class="grid gap-6 xl:grid-cols-3">

                <DashboardTopPlaces title="Lugares más buscados" subtitle="Mayor número de búsquedas realizadas."
                    :places="topSearchedPlaces" />

                <DashboardTopPlaces title="Lugares favoritos" subtitle="Lugares agregados con mayor frecuencia."
                    :places="topFavoritePlaces" />

                <DashboardTopPlaces title="Mejor valorados" subtitle="Promedio de valoraciones más alto."
                    :places="topRatedPlaces" />

            </div>

        </DashboardSection>

    </div>
</template>