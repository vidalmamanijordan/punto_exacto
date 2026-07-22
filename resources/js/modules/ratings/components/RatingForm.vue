<script setup lang="ts">
import AppCombobox from '@/components/shared/AppCombobox.vue';
import { Label } from '@/components/ui/label';
import type { User } from '../types/user';
import type { Place } from '../types/place';

defineProps<{
    form: {
        user_id: number | null;
        place_id: number | null;
        rating: number;
        comment: string | null;
    };

    users: User[];

    places: Place[];

    errors: Record<string, string>;
}>();
</script>

<template>
    <div class="space-y-4">

        <!-- Usuario -->
        <div class="space-y-1.5">
            <Label>Usuario</Label>
            <AppCombobox
                v-model="form.user_id"
                :options="users"
                placeholder="Buscar usuario..."
                empty-text="No se encontraron usuarios"
            />
            <p v-if="errors.user_id" class="text-xs text-destructive">{{ errors.user_id }}</p>
        </div>

        <!-- Lugar -->
        <div class="space-y-1.5">
            <Label>Lugar</Label>
            <AppCombobox
                v-model="form.place_id"
                :options="places"
                placeholder="Buscar lugar..."
                empty-text="No se encontraron lugares"
            />
            <p v-if="errors.place_id" class="text-xs text-destructive">{{ errors.place_id }}</p>
        </div>

        <!-- Calificación -->
        <div class="space-y-1.5">
            <Label>Calificación</Label>
            <select v-model="form.rating"
                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm transition focus:border-slate-400 focus:outline-none">
                <option :value="1">⭐ 1</option>
                <option :value="2">⭐⭐ 2</option>
                <option :value="3">⭐⭐⭐ 3</option>
                <option :value="4">⭐⭐⭐⭐ 4</option>
                <option :value="5">⭐⭐⭐⭐⭐ 5</option>
            </select>
            <p v-if="errors.rating" class="text-xs text-destructive">{{ errors.rating }}</p>
        </div>

        <!-- Comentario -->
        <div class="space-y-1.5">
            <Label>Comentario</Label>
            <textarea v-model="form.comment" rows="4" placeholder="Escriba un comentario..."
                class="w-full resize-none rounded-lg border border-slate-200 px-3 py-2 text-sm transition focus:border-slate-400 focus:outline-none" />
            <p v-if="errors.comment" class="text-xs text-destructive">{{ errors.comment }}</p>
        </div>

    </div>
</template>
