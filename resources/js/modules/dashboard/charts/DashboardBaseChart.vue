<script setup lang="ts">
import { computed } from 'vue';
import type { ApexOptions } from 'apexcharts';

const DEFAULT_COLORS = [
    '#2563EB',
    '#0EA5E9',
    '#10B981',
    '#F59E0B',
    '#EF4444',
    '#8B5CF6',
];

interface Props {
    type:
    | 'line'
    | 'area'
    | 'bar'
    | 'donut'
    | 'pie'
    | 'radar'
    | 'heatmap';

    title?: string;
    series: ApexAxisChartSeries | ApexNonAxisChartSeries;
    categories?: string[];
    colors?: string[];
    height?: number;
}

const props = withDefaults(defineProps<Props>(), {
    height: 320,
});

const chartOptions = computed<ApexOptions>(() => ({
    chart: {
        toolbar: {
            show: true,
        },
        zoom: {
            enabled: false,
        },
        animations: {
            enabled: true,
            easing: 'easeinout',
            speed: 700,
        },
        fontFamily: 'Inter, sans-serif',
    },

    colors: props.colors ?? DEFAULT_COLORS,

    stroke: {
        width: 3,
        curve: 'smooth',
    },

    fill: {
        opacity: 0.9,
    },

    dataLabels: {
        enabled: false,
    },

    grid: {
        borderColor: '#E5E7EB',
        strokeDashArray: 4,
    },

    legend: {
        position: 'bottom',
    },

    xaxis: {
        categories: props.categories,
        labels: {
            style: {
                colors: '#64748B',
            },
        },
    },

    yaxis: {
        labels: {
            style: {
                colors: '#64748B',
            },
        },
    },

    tooltip: {
        theme: 'light',
    },

    responsive: [
        {
            breakpoint: 768,
            options: {
                chart: {
                    height: 260,
                },
                legend: {
                    position: 'bottom',
                },
            },
        },
    ],
}));
</script>

<template>
    <ApexChart :type="type" :height="height" :options="chartOptions" :series="series" />
</template>