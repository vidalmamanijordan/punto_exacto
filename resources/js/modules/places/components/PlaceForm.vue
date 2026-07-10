<script setup lang="ts">
import type { Campus } from '@/modules/campus/types/campus';
import type { Category } from '@/modules/categories/types/category';
import AppCombobox from '@/components/shared/AppCombobox.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

defineProps<{
    form: {
        campus_id: number | null;
        category_id: number | null;
        name: string;
        description: string;
        building: string;
        floor: string;
        room: string;
        image: string;
        latitude: number | null;
        longitude: number | null;
        is_active: boolean;
    };
    campuses: Campus[];
    categories: Category[];
    errors: {
        campus_id: string;
        category_id: string;
        name: string;
        description: string;
        building: string;
        floor: string;
        room: string;
        image: string;
        latitude: string;
        longitude: string;
    };
    step: 1 | 2;
}>();

const selectClass = 'border-input h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm shadow-xs outline-none transition-[color,box-shadow] focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50';
</script>

<template>
    <!-- Paso 1: Información general -->
    <div v-if="step === 1" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <!-- Campus -->
            <div class="space-y-1.5">
                <Label>Campus</Label>
                <AppCombobox
                    v-model="form.campus_id"
                    :options="campuses"
                    placeholder="Buscar campus..."
                    empty-text="No se encontraron campus"
                />
                <p v-if="errors.campus_id" class="text-xs text-destructive">{{ errors.campus_id }}</p>
            </div>

            <!-- Categoría -->
            <div class="space-y-1.5">
                <Label>Categoría</Label>
                <AppCombobox
                    v-model="form.category_id"
                    :options="categories"
                    placeholder="Buscar categoría..."
                    empty-text="No se encontraron categorías"
                />
                <p v-if="errors.category_id" class="text-xs text-destructive">{{ errors.category_id }}</p>
            </div>
        </div>

        <!-- Nombre -->
        <div class="space-y-1.5">
            <Label for="place-name">Nombre del lugar</Label>
            <Input
                id="place-name"
                v-model="form.name"
                placeholder="Ej. Biblioteca Central"
                :aria-invalid="!!errors.name"
            />
            <p v-if="errors.name" class="text-xs text-destructive">{{ errors.name }}</p>
        </div>

        <!-- Descripción -->
        <div class="space-y-1.5">
            <Label for="place-description">Descripción</Label>
            <textarea
                id="place-description"
                v-model="form.description"
                rows="3"
                placeholder="Descripción breve del lugar..."
                :class="[selectClass, 'h-auto resize-none py-2 leading-relaxed']"
            />
            <p v-if="errors.description" class="text-xs text-destructive">{{ errors.description }}</p>
        </div>

        <!-- Estado -->
        <div class="flex items-center gap-3 rounded-lg border border-slate-100 bg-slate-50/60 px-4 py-3">
            <input
                id="is_active"
                v-model="form.is_active"
                type="checkbox"
                class="h-4 w-4 rounded border-slate-300 accent-slate-900"
            />
            <div>
                <label for="is_active" class="text-sm font-medium text-slate-700 cursor-pointer">
                    Lugar activo
                </label>
                <p class="text-xs text-slate-400">El lugar será visible en la plataforma</p>
            </div>
        </div>
    </div>

    <!-- Paso 2: Ubicación y detalles -->
    <div v-if="step === 2" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <!-- Edificio -->
            <div class="space-y-1.5">
                <Label for="place-building">Edificio</Label>
                <Input
                    id="place-building"
                    v-model="form.building"
                    placeholder="Ej. Bloque A"
                    :aria-invalid="!!errors.building"
                />
                <p v-if="errors.building" class="text-xs text-destructive">{{ errors.building }}</p>
            </div>

            <!-- Piso -->
            <div class="space-y-1.5">
                <Label for="place-floor">Piso</Label>
                <Input
                    id="place-floor"
                    v-model="form.floor"
                    placeholder="Ej. 2"
                    :aria-invalid="!!errors.floor"
                />
                <p v-if="errors.floor" class="text-xs text-destructive">{{ errors.floor }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <!-- Ambiente -->
            <div class="space-y-1.5">
                <Label for="place-room">Ambiente</Label>
                <Input
                    id="place-room"
                    v-model="form.room"
                    placeholder="Ej. A-204"
                    :aria-invalid="!!errors.room"
                />
                <p v-if="errors.room" class="text-xs text-destructive">{{ errors.room }}</p>
            </div>

            <!-- Imagen -->
            <div class="space-y-1.5">
                <Label for="place-image">URL de imagen</Label>
                <Input
                    id="place-image"
                    v-model="form.image"
                    placeholder="https://..."
                    :aria-invalid="!!errors.image"
                />
                <p v-if="errors.image" class="text-xs text-destructive">{{ errors.image }}</p>
            </div>
        </div>

        <!-- Coordenadas -->
        <div class="space-y-1.5">
            <Label class="text-slate-600">Coordenadas geográficas</Label>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <Label for="place-latitude" class="text-xs text-slate-400 font-normal">Latitud</Label>
                    <Input
                        id="place-latitude"
                        v-model.number="form.latitude"
                        type="number"
                        step="0.0000001"
                        placeholder="Ej. -12.0464"
                        :aria-invalid="!!errors.latitude"
                    />
                    <p v-if="errors.latitude" class="text-xs text-destructive">{{ errors.latitude }}</p>
                </div>

                <div class="space-y-1.5">
                    <Label for="place-longitude" class="text-xs text-slate-400 font-normal">Longitud</Label>
                    <Input
                        id="place-longitude"
                        v-model.number="form.longitude"
                        type="number"
                        step="0.0000001"
                        placeholder="Ej. -77.0428"
                        :aria-invalid="!!errors.longitude"
                    />
                    <p v-if="errors.longitude" class="text-xs text-destructive">{{ errors.longitude }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
