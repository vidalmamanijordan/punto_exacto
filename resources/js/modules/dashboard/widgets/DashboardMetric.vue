<script setup lang="ts">
defineProps<{
    label: string;
    value: number | string;
    icon?: any;
    color?: string;
}>();
</script>

<template>
    <div
        class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 transition-colors hover:bg-slate-100">
        <div class="flex items-center gap-3">
            <div v-if="icon" class="flex h-10 w-10 items-center justify-center rounded-lg"
                :class="color ?? 'bg-slate-200'">
                <component :is="icon" class="h-5 w-5 text-slate-700" />
            </div>
            <div>
                <p class="text-sm text-slate-500">
                    {{ label }}
                </p>
                <p class="text-lg font-semibold text-slate-800">
                    {{ value }}
                </p>
            </div>
        </div>
        <slot />
    </div>
</template>