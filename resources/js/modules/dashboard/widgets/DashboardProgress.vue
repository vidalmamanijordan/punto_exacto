<script setup lang="ts">
import { computed } from 'vue';
const props = defineProps<{
    title: string;
    value: number;
    max?: number;
    color?: string;
    showPercentage?: boolean;
}>();

const maxValue = computed(() => props.max ?? 100);
const percentage = computed(() => {
    if (maxValue.value <= 0) return 0;

    return Math.min(
        Math.round((props.value / maxValue.value) * 100),
        100
    );
});
const barColor = computed(() => {
    return props.color ?? 'bg-blue-500';
});
</script>

<template>
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-slate-600">
                {{ title }}
            </span>
            <span class="text-sm font-semibold text-slate-800">
                <template v-if="showPercentage">

                    {{ percentage }}%
                </template>
                <template v-else>
                    {{ value }}
                </template>
            </span>
        </div>
        <div class="h-3 overflow-hidden rounded-full bg-slate-200">
            <div class="h-full rounded-full transition-all duration-500" :class="barColor" :style="{
                width: percentage + '%'
            }" />
        </div>
    </div>
</template>