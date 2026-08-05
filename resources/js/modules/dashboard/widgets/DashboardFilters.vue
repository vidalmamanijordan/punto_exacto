<script setup lang="ts">
import { computed } from 'vue';
import {
    CalendarDays,
    Building2,
    FolderTree,
    RotateCw,
    FilterX,
} from '@lucide/vue';

export interface DashboardFiltersModel {
    period: string;
    campus: string | number;
    category: string | number;
}

const DEFAULT_FILTERS: DashboardFiltersModel = {
    period: '30',
    campus: 'all',
    category: 'all',
};

const DEFAULT_PERIODS = [
    { value: 7, label: 'Últimos 7 días' },
    { value: 30, label: 'Últimos 30 días' },
    { value: 90, label: 'Últimos 90 días' },
    { value: 365, label: 'Último año' },
];

const model = defineModel<DashboardFiltersModel>({
    required: true,
});

const props = defineProps<{
    campuses: {
        id: number;
        name: string;
    }[];

    categories: {
        id: number;
        name: string;
    }[];

    periods?: {
        value: number;
        label: string;
    }[];

    loading?: boolean;
}>();

const availablePeriods = computed(() =>
    props.periods && props.periods.length > 0 ? props.periods : DEFAULT_PERIODS,
);

const emit = defineEmits<{
    (e: 'refresh'): void;
}>();

const selectClass =
    'w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-100';

function resetFilters() {
    model.value = { ...DEFAULT_FILTERS };
    emit('refresh');
}
</script>

<template>
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">

            <div class="grid flex-1 gap-5 md:grid-cols-3">

                <!-- Período -->
                <div>
                    <label class="mb-2 flex items-center gap-2 text-sm font-medium text-slate-600">
                        <CalendarDays class="h-4 w-4" />
                        Período
                    </label>

                    <select
                        v-model="model.period"
                        :class="selectClass"
                    >
                        <option
                            v-for="period in availablePeriods"
                            :key="period.value"
                            :value="String(period.value)"
                        >
                            {{ period.label }}
                        </option>
                    </select>
                </div>

                <!-- Campus -->
                <div>
                    <label class="mb-2 flex items-center gap-2 text-sm font-medium text-slate-600">
                        <Building2 class="h-4 w-4" />
                        Campus
                    </label>

                    <select
                        v-model="model.campus"
                        :class="selectClass"
                    >
                        <option value="all">
                            Todos
                        </option>

                        <option
                            v-for="campus in campuses"
                            :key="campus.id"
                            :value="campus.id"
                        >
                            {{ campus.name }}
                        </option>
                    </select>
                </div>

                <!-- Categoría -->
                <div>
                    <label class="mb-2 flex items-center gap-2 text-sm font-medium text-slate-600">
                        <FolderTree class="h-4 w-4" />
                        Categoría
                    </label>

                    <select
                        v-model="model.category"
                        :class="selectClass"
                    >
                        <option value="all">
                            Todas
                        </option>

                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                </div>

            </div>

            <!-- Botones -->
            <div class="flex gap-3">

                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2 text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60"
                    :disabled="loading"
                    @click="emit('refresh')"
                >
                    <RotateCw
                        class="h-4 w-4"
                        :class="{ 'animate-spin': loading }"
                    />

                    Actualizar
                </button>

                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-5 py-2 transition hover:bg-slate-50"
                    @click="resetFilters"
                >
                    <FilterX class="h-4 w-4" />

                    Limpiar
                </button>

            </div>

        </div>
    </div>
</template>