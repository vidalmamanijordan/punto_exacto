<script setup lang="ts">
import { reactive, ref, watch, computed } from 'vue';
import { toast } from 'vue-sonner';
import CategoryForm from './CategoryForm.vue';
import { categoryService } from '../services/category.service';
import type { Category } from '../types/category';


const props = defineProps<{
    open: boolean;
    category: Category | null;
}>();

const isEditing = computed(() => !!props.category);

const emit = defineEmits<{
    close: [];
    saved: [];
}>();

const form = reactive({
    name: '',
    description: '',
    is_active: true,
});

const resetForm = () => {
    form.name = '';
    form.description = '';
    form.is_active = true;

    errors.name = '';
    errors.description = '';
};

watch(
    () => props.category,
    (category) => {
        if (category) {
            form.name = category.name;
            form.description = category.description ?? '';
            form.is_active = category.is_active;
        } else {
            form.name = '';
            form.description = '';
            form.is_active = true;
        }
    },
    { immediate: true }
);

const loading = ref(false);

const errors = reactive({
    name: '',
    description: '',
});

const saveCategory = async () => {
    try {
        loading.value = true;
        errors.name = '';
        errors.description = '';
        if (isEditing.value && props.category) {
            await categoryService.update(
                props.category.id,
                {
                    name: form.name,
                    description: form.description,
                    is_active: form.is_active,
                }
            );

            toast.success('Categoría actualizada', {
                description: `"${form.name}" fue actualizada exitosamente.`,
            });
        } else {
            await categoryService.create({
                name: form.name,
                description: form.description,
                is_active: form.is_active,
            });
            toast.success('Categoría creada', {
                description: `"${form.name}" fue registrada exitosamente.`,
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
            errors.description =
                validationErrors.description?.[0] ?? '';
            toast.error('Formulario inválido', {
                description: 'Revisa los campos marcados y corrígelos antes de continuar.',
            });
            return;
        }
        console.error(error);
        toast.error('No se pudo guardar', {
            description: 'Ocurrió un error inesperado. Inténtalo de nuevo.',
        });
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="w-full max-w-lg rounded-lg bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold">
                    {{ isEditing ? 'Editar Categoría' : 'Nueva Categoría' }}
                </h2>

                <button class="text-gray-500 hover:text-gray-700" @click="emit('close')">
                    ✕
                </button>
            </div>

            <CategoryForm :form="form" :errors="errors" />

            <div class="mt-6 flex justify-end gap-2">
                <button class="rounded border px-4 py-2" @click="emit('close')">
                    Cancelar
                </button>

                <button :disabled="loading" @click="saveCategory" class="rounded bg-blue-600 px-4 py-2 text-white">
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
