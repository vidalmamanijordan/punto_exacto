<script setup lang="ts">
import { TabsRoot, TabsList, TabsTrigger, TabsIndicator } from 'reka-ui';
import {
    BarChart3,
    ChartColumnIncreasing,
    Trophy,
    MapPinned,
} from '@lucide/vue';

export type DashboardTab =
    | 'summary'
    | 'reports'
    | 'rankings'
    | 'campus';

const modelValue = defineModel<DashboardTab>({ default: 'summary' });

const tabs = [
    {
        value: 'summary',
        label: 'Resumen',
        icon: BarChart3,
    },
    {
        value: 'reports',
        label: 'Reportes',
        icon: ChartColumnIncreasing,
    },
    {
        value: 'rankings',
        label: 'Rankings',
        icon: Trophy,
    },
    {
        value: 'campus',
        label: 'Campus',
        icon: MapPinned,
    },
] as const;
</script>

<template>
    <TabsRoot v-model="modelValue">
        <TabsList
            class="relative flex items-center gap-1 rounded-xl border border-slate-200 bg-slate-100 p-1 shadow-sm">
            <!-- Indicador animado (reka-ui sets --reka-tabs-indicator-size and --reka-tabs-indicator-position) -->
            <TabsIndicator
                class="absolute bottom-1 left-0 h-[calc(100%-8px)] rounded-lg bg-white shadow-sm transition-all duration-300 ease-in-out"
                style="width: var(--reka-tabs-indicator-size); transform: translateX(var(--reka-tabs-indicator-position));" />

            <TabsTrigger v-for="tab in tabs" :key="tab.value" :value="tab.value"
                class="relative z-10 flex cursor-pointer items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-slate-500 outline-none transition-colors duration-200 select-none hover:text-slate-700 data-[state=active]:text-slate-900 data-[state=active]:font-semibold">
                <component :is="tab.icon" class="h-4 w-4 shrink-0 transition-colors duration-200"
                    :class="modelValue === tab.value ? 'text-blue-600' : 'text-slate-400'" />
                {{ tab.label }}
            </TabsTrigger>
        </TabsList>
    </TabsRoot>
</template>
