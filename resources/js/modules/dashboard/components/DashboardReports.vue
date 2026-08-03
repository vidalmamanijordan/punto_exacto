<script setup lang="ts">
import type { DashboardData } from '../types/dashboard';

defineProps<{
    dashboard: DashboardData;
}>();
</script>

<template>
    <div class="space-y-6">
        <!-- Encabezado -->
        <div class="rounded-xl border bg-white p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-800">
                Reportes Estadísticos
            </h2>
            <p class="mt-2 text-sm text-slate-500">
                Visualiza el comportamiento del sistema mediante gráficos y estadísticas.
            </p>
        </div>
        <!-- Primera fila -->
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-xl border bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold">
                    Búsquedas por día
                </h3>
                <div class="flex h-72 items-center justify-center rounded-lg border border-dashed text-slate-400">
                    Próximamente gráfico de búsquedas
                </div>
            </div>
            <div class="rounded-xl border bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold">
                    Favoritos por día
                </h3>
                <div class="flex h-72 items-center justify-center rounded-lg border border-dashed text-slate-400">
                    Próximamente gráfico de favoritos
                </div>
            </div>
        </div>
        <!-- Segunda fila -->
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-xl border bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold">
                    Valoraciones por día
                </h3>
                <div class="flex h-72 items-center justify-center rounded-lg border border-dashed text-slate-400">
                    Próximamente gráfico de valoraciones
                </div>
            </div>
            <div class="rounded-xl border bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold">
                    Usuarios registrados
                </h3>
                <div class="flex h-72 items-center justify-center rounded-lg border border-dashed text-slate-400">
                    Próximamente gráfico de usuarios
                </div>
            </div>
        </div>
    </div>
</template>