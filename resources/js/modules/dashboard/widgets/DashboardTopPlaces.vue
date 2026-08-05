<script setup lang="ts">
import {
    Trophy,
    Medal,
    Award,
} from '@lucide/vue';

interface TopPlace {
    id: number;
    name: string;
    total: number;
}

const props = defineProps<{
    title: string;
    subtitle?: string;
    places: TopPlace[];
}>();

const getPositionIcon = (index: number) => {
    switch (index) {
        case 0:
            return Trophy;
        case 1:
            return Medal;
        case 2:
            return Award;
        default:
            return null;
    }
};

const getPositionColor = (index: number) => {
    switch (index) {
        case 0:
            return 'bg-yellow-100 text-yellow-600';
        case 1:
            return 'bg-slate-200 text-slate-700';
        case 2:
            return 'bg-orange-100 text-orange-600';
        default:
            return 'bg-blue-100 text-blue-700';
    }
};

const formatValue = (value: number | string) => {
    const num = Number(value);

    if (Number.isInteger(num)) {
        return num.toLocaleString();
    }

    return num.toFixed(2);
};
</script>

<template>
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <!-- Header -->

        <div class="border-b border-slate-200 px-6 py-5">

            <div class="flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100">
                    <Trophy class="h-5 w-5 text-amber-600" />
                </div>

                <div>

                    <h2 class="text-lg font-semibold text-slate-800">
                        {{ title }}
                    </h2>

                    <p class="text-sm text-slate-500">
                        {{ subtitle || 'Ranking de los lugares más populares.' }}
                    </p>

                </div>

            </div>

        </div>

        <!-- Body -->

        <div class="divide-y divide-slate-100">

            <div v-for="(place, index) in places" :key="place.id"
                class="flex items-center justify-between px-6 py-4 transition-colors hover:bg-slate-50">

                <div class="flex items-center gap-4">

                    <div class="flex h-10 w-10 items-center justify-center rounded-full font-semibold"
                        :class="getPositionColor(index)">

                        <component v-if="getPositionIcon(index)" :is="getPositionIcon(index)" class="h-5 w-5" />

                        <span v-else>
                            {{ index + 1 }}
                        </span>

                    </div>

                    <div>

                        <h3 class="font-medium text-slate-800">
                            {{ place.name }}
                        </h3>

                        <p class="text-xs text-slate-400">
                            Puesto #{{ index + 1 }}
                        </p>

                    </div>

                </div>

                <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700">
                    {{ formatValue(place.total) }}
                </span>

            </div>

            <div v-if="places.length === 0" class="px-6 py-12 text-center text-sm text-slate-400">
                No existen datos para mostrar.
            </div>

        </div>

    </div>
</template>