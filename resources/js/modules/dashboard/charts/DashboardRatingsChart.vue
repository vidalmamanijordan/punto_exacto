<script setup lang="ts">
import { computed } from 'vue';
import DashboardBaseChart from './DashboardBaseChart.vue';

interface RatingChartItem {
    date: string;
    total: number;
}

const props = defineProps<{
    data: RatingChartItem[];
}>();

/*
|--------------------------------------------------------------------------
| Categorías (Eje X)
|--------------------------------------------------------------------------
*/

const categories = computed(() =>
    props.data.map(item => item.date)
);

/*
|--------------------------------------------------------------------------
| Series del gráfico
|--------------------------------------------------------------------------
*/

const series = computed(() => [
    {
        name: 'Valoraciones',
        data: props.data.map(item => item.total),
    },
]);

/*
|--------------------------------------------------------------------------
| Color corporativo
|--------------------------------------------------------------------------
*/

const colors = ['#F59E0B']; // Amber-500
</script>

<template>
    <DashboardBaseChart title="Valoraciones" type="bar" :series="series" :categories="categories" :colors="colors"
        :height="340" />
</template>
