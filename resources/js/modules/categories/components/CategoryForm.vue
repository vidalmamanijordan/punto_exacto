<script setup lang="ts">
defineProps<{
    form: {
        name: string;
        description: string;
        is_active: boolean;
    };
    errors: {
        name: string;
        description: string;
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
                placeholder="Ingrese el nombre" />
            <p v-if="errors.name" class="mt-1 text-sm text-red-500">
                {{ errors.name }}
            </p>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">
                Descripción
            </label>

            <textarea v-model="form.description" rows="3" class="w-full rounded-lg border px-3 py-2"
                placeholder="Ingrese una descripción" />
            <p v-if="errors.description" class="mt-1 text-sm text-red-500">
                {{ errors.description }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            <input id="is_active" v-model="form.is_active" type="checkbox" />

            <label for="is_active">
                Activo
            </label>
        </div>

    </div>
</template>
