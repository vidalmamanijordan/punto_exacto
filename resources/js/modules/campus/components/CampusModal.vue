<script setup lang="ts">
import { reactive, ref, watch, computed } from 'vue';
import { toast } from 'vue-sonner';

import CampusForm from './CampusForm.vue';

import { campusService } from '../services/campus.service';

import type {
    Campus,
    CampusFormData,
} from '../types/campus';

const props = defineProps<{
    open: boolean;
    campus: Campus | null;
}>();

const emit = defineEmits<{
    close: [];
    saved: [];
}>();

const isEditing = computed(
    () => !!props.campus
);

const form = reactive<CampusFormData>({
    name: '',
    code: '',
    address: '',
    latitude: null,
    longitude: null,
    is_active: true,
});

const errors = reactive({
    name: '',
    code: '',
    address: '',
    latitude: '',
    longitude: '',
});

const loading = ref(false);

const resetForm = () => {
    form.name = '';
    form.code = '';
    form.address = '';
    form.latitude = null;
    form.longitude = null;
    form.is_active = true;

    errors.name = '';
    errors.code = '';
    errors.address = '';
    errors.latitude = '';
    errors.longitude = '';
};

watch(
    () => props.campus,
    (campus) => {
        if (campus) {
            form.name = campus.name;
            form.code = campus.code;
            form.address = campus.address ?? '';
            form.latitude = campus.latitude;
            form.longitude = campus.longitude;
            form.is_active = campus.is_active;
        } else {
            resetForm();
        }
    },
    { immediate: true }
);

const saveCampus = async () => {
    try {
        loading.value = true;

        errors.name = '';
        errors.code = '';
        errors.address = '';
        errors.latitude = '';
        errors.longitude = '';

        if (isEditing.value && props.campus) {
            await campusService.update(
                props.campus.id,
                form
            );

            toast.success('Campus actualizado', {
                description:
                    `"${form.name}" fue actualizado exitosamente.`,
            });
        } else {
            await campusService.create(form);

            toast.success('Campus creado', {
                description:
                    `"${form.name}" fue registrado exitosamente.`,
            });
        }

        resetForm();

        emit('saved');
        emit('close');
    } catch (error: any) {
        if (error.response?.status === 422) {
            const validationErrors =
                error.response.data.errors;

            errors.name =
                validationErrors.name?.[0] ?? '';

            errors.code =
                validationErrors.code?.[0] ?? '';

            errors.address =
                validationErrors.address?.[0] ?? '';

            errors.latitude =
                validationErrors.latitude?.[0] ?? '';

            errors.longitude =
                validationErrors.longitude?.[0] ?? '';

            toast.error('Formulario inválido', {
                description:
                    'Revisa los campos marcados y corrígelos antes de continuar.',
            });

            return;
        }

        console.error(error);

        toast.error('No se pudo guardar', {
            description:
                'Ocurrió un error inesperado. Inténtalo nuevamente.',
        });
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="w-full max-w-2xl rounded-lg bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold">
                    {{
                        isEditing
                            ? 'Editar Campus'
                            : 'Nuevo Campus'
                    }}
                </h2>

                <button class="text-gray-500 hover:text-gray-700" @click="emit('close')">
                    ✕
                </button>
            </div>

            <CampusForm :form="form" :errors="errors" />

            <div class="mt-6 flex justify-end gap-2">
                <button class="rounded border px-4 py-2" @click="emit('close')">
                    Cancelar
                </button>

                <button :disabled="loading" @click="saveCampus" class="rounded bg-blue-600 px-4 py-2 text-white">
                    {{
                        loading
                            ? 'Guardando...'
                            : isEditing
                                ? 'Actualizar'
                                : 'Guardar'
                    }}
                </button>
            </div>
        </div>
    </div>
</template>