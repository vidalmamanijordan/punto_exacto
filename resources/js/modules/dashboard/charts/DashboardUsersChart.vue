<script setup lang="ts">
import { computed } from 'vue';
import DashboardBaseChart from './DashboardBaseChart.vue';

interface UserChartItem {
    date: string;
    total: number;
}

const props = defineProps<{
    data: UserChartItem[];
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
| Series
|--------------------------------------------------------------------------
*/

const series = computed(() => [
    {
        name: 'Usuarios',
        data: props.data.map(item => item.total),
    },
]);

/*
|--------------------------------------------------------------------------
| Color corporativo
|--------------------------------------------------------------------------
*/

const colors = ['#3B82F6']; // Blue-500
</script>

<template>
    <DashboardBaseChart title="Usuarios registrados" type="line" :series="series" :categories="categories"
        :colors="colors" :height="340" />
</template>
