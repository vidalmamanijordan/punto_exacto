<script setup lang="ts">
import type { LucideIcon } from '@lucide/vue';

defineProps<{
    title: string;
    value: number | string;
    icon: LucideIcon;
}>();
</script>

<template>
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">
                    {{ title }}
                </p>
                <h2 class="mt-2 text-3xl font-bold text-slate-800">
                    {{ value }}
                </h2>
            </div>
            <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-blue-50">
                <component :is="icon" class="h-7 w-7 text-blue-600" />
            </div>
        </div>
    </div>
</template>
