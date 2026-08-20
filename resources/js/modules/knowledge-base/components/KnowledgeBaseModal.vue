<script setup lang="ts">
import { computed, reactive, watch } from 'vue';
import { X } from '@lucide/vue';

import KnowledgeBaseForm from './KnowledgeBaseForm.vue';

import type {
    KnowledgeBase,
    KnowledgeBaseFormData,
} from '../types/knowledge-base';

interface Props {
    open: boolean;
    knowledgeBase?: KnowledgeBase | null;
    processing?: boolean;
    errors?: Record<string, string>;
}

const props = withDefaults(defineProps<Props>(), {
    knowledgeBase: null,
    processing: false,
    errors: () => ({}),
});

const emit = defineEmits<{
    close: [];
    save: [form: KnowledgeBaseFormData];
}>();

/*
|--------------------------------------------------------------------------
| Formulario
|--------------------------------------------------------------------------
*/

const form = reactive<KnowledgeBaseFormData>({
    title: '',
    content: '',
    is_active: true,
});

/*
|--------------------------------------------------------------------------
| Estado
|--------------------------------------------------------------------------
*/

const isEditing = computed(() => {
    return props.knowledgeBase !== null;
});

const modalTitle = computed(() => {
    return isEditing.value
        ? 'Editar conocimiento'
        : 'Nuevo conocimiento';
});

const modalDescription = computed(() => {
    return isEditing.value
        ? 'Actualiza la información institucional utilizada por el sistema.'
        : 'Registra información oficial que podrá ser utilizada por la Inteligencia Artificial.';
});

/*
|--------------------------------------------------------------------------
| Inicializar formulario
|--------------------------------------------------------------------------
*/

function initializeForm(): void {
    if (props.knowledgeBase) {
        form.title = props.knowledgeBase.title;
        form.content = props.knowledgeBase.content;
        form.is_active = props.knowledgeBase.is_active;

        return;
    }

    resetForm();
}

/*
|--------------------------------------------------------------------------
| Reset
|--------------------------------------------------------------------------
*/

function resetForm(): void {
    form.title = '';
    form.content = '';
    form.is_active = true;
}

/*
|--------------------------------------------------------------------------
| Guardar
|--------------------------------------------------------------------------
*/

function handleSave(): void {
    emit('save', {
        title: form.title.trim(),
        content: form.content.trim(),
        is_active: form.is_active,
    });
}

/*
|--------------------------------------------------------------------------
| Cerrar
|--------------------------------------------------------------------------
*/

function handleClose(): void {
    if (props.processing) {
        return;
    }

    emit('close');
}

/*
|--------------------------------------------------------------------------
| Watchers
|--------------------------------------------------------------------------
*/

watch(
    () => props.open,
    (open) => {
        if (open) {
            initializeForm();
        }
    },
);

watch(
    () => props.knowledgeBase,
    () => {
        if (props.open) {
            initializeForm();
        }
    },
);
</script>

<template>
    <!-- Modal -->
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="handleClose" />

        <!-- Contenedor -->
        <div class="relative z-10 w-full max-w-2xl overflow-hidden rounded-xl bg-white shadow-2xl">
            <!-- Header -->
            <div class="flex items-start justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <h2 class="text-xl font-semibold text-slate-800">
                        {{ modalTitle }}
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ modalDescription }}
                    </p>
                </div>

                <button type="button"
                    class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 disabled:cursor-not-allowed disabled:opacity-50"
                    :disabled="processing" @click="handleClose">
                    <X class="h-5 w-5" />
                </button>
            </div>

            <!-- Body -->
            <div class="max-h-[70vh] overflow-y-auto px-6 py-6">
                <KnowledgeBaseForm :form="form" :errors="errors" :processing="processing" />
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4">
                <button type="button"
                    class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50"
                    :disabled="processing" @click="handleClose">
                    Cancelar
                </button>

                <button type="button"
                    class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-medium text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                    :disabled="processing" @click="handleSave">
                    <span v-if="processing">
                        Guardando...
                    </span>

                    <span v-else>
                        {{ isEditing ? 'Actualizar' : 'Guardar' }}
                    </span>
                </button>
            </div>
        </div>
    </div>
</template>
