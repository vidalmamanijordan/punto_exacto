<script setup lang="ts">
import { computed } from 'vue';

import DashboardBaseChart from './DashboardBaseChart.vue';

import type {
    DashboardChartPoint,
} from '../types/dashboard';

const props = defineProps<{
    data: DashboardChartPoint[];
}>();

const categories = computed(() =>
    props.data.map(item => item.date)
);

const series = computed(() => [
    {
        name: 'Búsquedas',
        data: props.data.map(item => item.total),
    },
]);
</script>

<template>
    <DashboardBaseChart type="line" :series="series" :categories="categories" :height="340" />
</template>