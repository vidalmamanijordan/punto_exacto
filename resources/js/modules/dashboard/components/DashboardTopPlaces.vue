<script setup lang="ts">
import { Trophy } from '@lucide/vue';

interface TopPlace {
    id: number;
    name: string;
    total: number;
}

defineProps<{
    title: string;
    places: TopPlace[];
}>();
</script>

<template>
    <div class="rounded-xl border bg-white shadow-sm">

        <!-- Header -->
        <div class="border-b px-6 py-4">
            <div class="flex items-center gap-3">
                <Trophy class="h-5 w-5 text-yellow-500" />
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">
                        {{ title }}
                    </h2>
                    <p class="text-sm text-slate-500">
                        Ranking de los lugares más populares.
                    </p>
                </div>
            </div>
        </div>

        <!-- Body -->

        <div class="divide-y">
            <div v-for="(place, index) in places" :key="place.id"
                class="flex items-center justify-between px-6 py-4 hover:bg-slate-50">
                <div class="flex items-center gap-4">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">
                        {{ index + 1 }}
                    </div>
                    <div>
                        <h3 class="font-medium text-slate-800">
                            {{ place.name }}
                        </h3>
                    </div>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700">
                    {{ place.total }}
                </span>
            </div>
            <div v-if="places.length === 0" class="px-6 py-10 text-center text-slate-400">
                No existen datos para mostrar.
            </div>
        </div>
    </div>
</template>
