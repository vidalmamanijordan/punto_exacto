<script setup lang="ts">
import AppCombobox from '@/components/shared/AppCombobox.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { User } from '../types/user';
import type { Place } from '../types/place';

defineProps<{
    form: {
        user_id: number | null;
        place_id: number | null;
        search_text: string;
    };
    users: User[];
    places: Place[];
    errors: {
        user_id: string;
        place_id: string;
        search_text: string;
    };
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

        <!-- Texto buscado -->
        <div class="space-y-1.5">
            <Label for="search-text">Texto buscado</Label>
            <Input
                id="search-text"
                v-model="form.search_text"
                placeholder="Ej.: Biblioteca Central"
                :aria-invalid="!!errors.search_text"
            />
            <p v-if="errors.search_text" class="text-xs text-destructive">{{ errors.search_text }}</p>
        </div>

    </div>
</template>
