<script setup lang="ts">
defineProps<{
    title: string;
    description?: string;
}>();
</script>
<template>
    <div class="rounded-xl border bg-white shadow-sm transition-shadow hover:shadow-md">
        <!-- Header -->
        <div class="flex items-center justify-between border-b px-6 py-4">
            <div>
                <h3 class="text-lg font-semibold text-slate-800">
                    {{ title }}
                </h3>
                <p v-if="description" class="mt-1 text-sm text-slate-500">
                    {{ description }}
                </p>
            </div>
            <slot name="actions" />
        </div>
        <!-- Body -->
        <div class="p-6">
            <slot />
        </div>
        <!-- Footer -->
        <div v-if="$slots.footer" class="border-t bg-slate-50 px-6 py-3">
            <slot name="footer" />
        </div>
    </div>
</template>