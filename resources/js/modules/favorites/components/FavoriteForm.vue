<script setup lang="ts">
import type { User } from '../types/user';
import type { Place } from '../types/place';

defineProps<{
    form: {
        user_id: number | null;
        place_id: number | null;
    };
    users: User[];
    places: Place[];
    errors: Record<string, string>;
}>();
</script>

<template>
    <div class="space-y-6">

        <!-- Usuario -->
        <div>

            <label class="mb-1 block text-sm text-slate-600">
                Usuario
            </label>

            <select v-model="form.user_id"
                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none">

                <option :value="null">
                    Seleccione un usuario
                </option>

                <option v-for="user in users" :key="user.id" :value="user.id">
                    {{ user.name }}
                </option>

            </select>

            <p v-if="errors.user_id" class="mt-1 text-xs text-red-500">
                {{ errors.user_id }}
            </p>

        </div>

        <!-- Lugar -->
        <div>

            <label class="mb-1 block text-sm text-slate-600">
                Lugar
            </label>

            <select v-model="form.place_id"
                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none">

                <option :value="null">
                    Seleccione un lugar
                </option>

                <option v-for="place in places" :key="place.id" :value="place.id">
                    {{ place.name }}
                </option>

            </select>

            <p v-if="errors.place_id" class="mt-1 text-xs text-red-500">
                {{ errors.place_id }}
            </p>

        </div>

    </div>
</template>
