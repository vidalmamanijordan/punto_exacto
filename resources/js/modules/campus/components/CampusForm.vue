<script setup lang="ts">
defineProps<{
    form: {
        name: string;
        code: string;
        address: string | null;
        latitude: number | null;
        longitude: number | null;
        is_active: boolean;
    };

    errors: {
        name: string;
        code: string;
        address: string;
        latitude: string;
        longitude: string;
    };
}>();
</script>

<template>
    <div class="space-y-4">

        <div>
            <label class="mb-1 block text-sm font-medium">
                Nombre
            </label>

            <input v-model="form.name" type="text" class="w-full rounded-lg border px-3 py-2"
                placeholder="Ingrese el nombre del campus" />

            <p v-if="errors.name" class="mt-1 text-sm text-red-500">
                {{ errors.name }}
            </p>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">
                Código
            </label>

            <input v-model="form.code" type="text" class="w-full rounded-lg border px-3 py-2" placeholder="Ej. LIM" />

            <p v-if="errors.code" class="mt-1 text-sm text-red-500">
                {{ errors.code }}
            </p>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">
                Dirección
            </label>

            <textarea v-model="form.address" rows="3" class="w-full rounded-lg border px-3 py-2"
                placeholder="Ingrese la dirección" />

            <p v-if="errors.address" class="mt-1 text-sm text-red-500">
                {{ errors.address }}
            </p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="mb-1 block text-sm font-medium">
                    Latitud
                </label>

                <input v-model.number="form.latitude" type="number" step="any"
                    class="w-full rounded-lg border px-3 py-2" placeholder="-15.500000" />

                <p v-if="errors.latitude" class="mt-1 text-sm text-red-500">
                    {{ errors.latitude }}
                </p>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">
                    Longitud
                </label>

                <input v-model.number="form.longitude" type="number" step="any"
                    class="w-full rounded-lg border px-3 py-2" placeholder="-70.133333" />

                <p v-if="errors.longitude" class="mt-1 text-sm text-red-500">
                    {{ errors.longitude }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <input id="is_active" v-model="form.is_active" type="checkbox" />

            <label for="is_active">
                Activo
            </label>
        </div>

    </div>
</template>