<script setup lang="ts">
defineProps<{
    title: string;
    description?: string;
}>();
</script>

<template>
    <section class="rounded-xl border bg-white shadow-sm">
        <header class="border-b px-6 py-4">
            <h2 class="text-lg font-semibold text-slate-800">
                {{ title }}
            </h2>
            <p v-if="description" class="mt-1 text-sm text-slate-500">
                {{ description }}
            </p>
        </header>
        <div class="p-6">
            <slot />
        </div>
    </section>
</template>