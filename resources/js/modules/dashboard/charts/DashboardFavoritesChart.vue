<script setup lang="ts">
import { computed } from 'vue';
import DashboardBaseChart from './DashboardBaseChart.vue';

interface FavoriteChartItem {
    date: string;
    total: number;
}

const props = defineProps<{
    data: FavoriteChartItem[];
}>();

/*
|--------------------------------------------------------------------------
| Categorías del eje X
|--------------------------------------------------------------------------
*/

const categories = computed(() =>
    props.data.map(item => item.date)
);

/*
|--------------------------------------------------------------------------
| Series para ApexCharts
|--------------------------------------------------------------------------
*/

const series = computed(() => [
    {
        name: 'Favoritos',
        data: props.data.map(item => item.total),
    },
]);

/*
|--------------------------------------------------------------------------
| Color del gráfico
|--------------------------------------------------------------------------
*/

const colors = ['#EF4444']; // Tailwind Red-500
</script>

<template>
    <DashboardBaseChart title="Favoritos" type="area" :series="series" :categories="categories" :colors="colors"
        :height="340" />
</template>
