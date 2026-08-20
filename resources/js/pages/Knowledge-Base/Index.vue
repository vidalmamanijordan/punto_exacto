<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Plus, RefreshCw } from '@lucide/vue';
import { toast } from 'vue-sonner';
import KnowledgeBaseTable from '../../modules/knowledge-base/components/KnowledgeBaseTable.vue';
import KnowledgeBaseModal from '../../modules/knowledge-base/components/KnowledgeBaseModal.vue';
import { knowledgeBaseService } from '../../modules/knowledge-base/services/knowledge-base.service';
import type {
    KnowledgeBase,
    KnowledgeBaseFormData,
} from '../../modules/knowledge-base/types/knowledge-base';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Base de Conocimiento',
                href: '/knowledge-base',
            },
        ],
    },
});

/*
|--------------------------------------------------------------------------
| Estado
|--------------------------------------------------------------------------
*/

const knowledgeBases = ref<KnowledgeBase[]>([]);

const loading = ref(false);
const processing = ref(false);

const modalOpen = ref(false);

const selectedKnowledgeBase = ref<KnowledgeBase | null>(null);

const errors = ref<Record<string, string>>({});

/*
|--------------------------------------------------------------------------
| Obtener registros
|--------------------------------------------------------------------------
*/

async function loadKnowledgeBases(): Promise<void> {
    loading.value = true;

    try {
        knowledgeBases.value = await knowledgeBaseService.getAll();
    } catch (error) {
        console.error(
            'Error al cargar la Base de Conocimiento:',
            error,
        );

        toast.error(
            'No se pudo cargar la Base de Conocimiento.',
        );
    } finally {
        loading.value = false;
    }
}

/*
|--------------------------------------------------------------------------
| Abrir modal para crear
|--------------------------------------------------------------------------
*/

function openCreateModal(): void {
    selectedKnowledgeBase.value = null;
    errors.value = {};
    modalOpen.value = true;
}

/*
|--------------------------------------------------------------------------
| Abrir modal para editar
|--------------------------------------------------------------------------
*/

function openEditModal(
    knowledgeBase: KnowledgeBase,
): void {
    selectedKnowledgeBase.value = knowledgeBase;
    errors.value = {};
    modalOpen.value = true;
}

/*
|--------------------------------------------------------------------------
| Cerrar modal
|--------------------------------------------------------------------------
*/

function closeModal(): void {
    if (processing.value) {
        return;
    }

    modalOpen.value = false;
    selectedKnowledgeBase.value = null;
    errors.value = {};
}

/*
|--------------------------------------------------------------------------
| Guardar
|--------------------------------------------------------------------------
*/

async function handleSave(
    form: KnowledgeBaseFormData,
): Promise<void> {
    processing.value = true;
    errors.value = {};

    try {
        if (selectedKnowledgeBase.value) {
            await knowledgeBaseService.update(
                selectedKnowledgeBase.value.id,
                form,
            );

            toast.success(
                'Conocimiento actualizado correctamente.',
            );
        } else {
            await knowledgeBaseService.create(form);

            toast.success(
                'Conocimiento creado correctamente.',
            );
        }

        closeModal();

        await loadKnowledgeBases();
    } catch (error: any) {
        console.error(
            'Error al guardar el conocimiento:',
            error,
        );

        /*
        |--------------------------------------------------------------------------
        | Errores de validación Laravel
        |--------------------------------------------------------------------------
        */

        if (
            error?.response?.status === 422 &&
            error?.response?.data?.errors
        ) {
            const validationErrors =
                error.response.data.errors;

            const normalizedErrors: Record<string, string> = {};

            Object.entries(validationErrors).forEach(
                ([field, messages]) => {
                    if (Array.isArray(messages)) {
                        normalizedErrors[field] =
                            messages[0] ?? '';
                    } else {
                        normalizedErrors[field] =
                            String(messages);
                    }
                },
            );

            errors.value = normalizedErrors;

            toast.error(
                'Revisa los campos del formulario.',
            );

            return;
        }

        toast.error(
            'No se pudo guardar el conocimiento.',
        );
    } finally {
        processing.value = false;
    }
}

/*
|--------------------------------------------------------------------------
| Eliminar
|--------------------------------------------------------------------------
*/

async function handleDelete(
    knowledgeBase: KnowledgeBase,
): Promise<void> {
    const confirmed = window.confirm(
        `¿Estás seguro de eliminar "${knowledgeBase.title}"?`,
    );

    if (!confirmed) {
        return;
    }

    try {
        loading.value = true;

        await knowledgeBaseService.delete(
            knowledgeBase.id,
        );

        toast.success(
            'Conocimiento eliminado correctamente.',
        );

        await loadKnowledgeBases();
    } catch (error) {
        console.error(
            'Error al eliminar el conocimiento:',
            error,
        );

        toast.error(
            'No se pudo eliminar el conocimiento.',
        );
    } finally {
        loading.value = false;
    }
}

/*
|--------------------------------------------------------------------------
| Inicialización
|--------------------------------------------------------------------------
*/

onMounted(() => {
    loadKnowledgeBases();
});
</script>

<template>

    <Head title="Base de Conocimiento" />

    <div class="space-y-6 p-6">

        <!-- ============================================================= -->
        <!-- Encabezado -->
        <!-- ============================================================= -->

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Base de Conocimiento
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Administra la información institucional
                    que podrá utilizar la Inteligencia Artificial.
                </p>
            </div>

            <div class="flex items-center gap-3">

                <!-- Actualizar -->

                <button type="button"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
                    :disabled="loading" @click="loadKnowledgeBases">
                    <RefreshCw class="h-4 w-4" :class="{
                        'animate-spin': loading,
                    }" />

                    Actualizar
                </button>

                <!-- Nuevo -->

                <button type="button"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                    @click="openCreateModal">
                    <Plus class="h-4 w-4" />

                    Nuevo conocimiento
                </button>

            </div>
        </div>

        <!-- ============================================================= -->
        <!-- Tabla -->
        <!-- ============================================================= -->

        <KnowledgeBaseTable :items="knowledgeBases" :loading="loading" @edit="openEditModal"
            @delete="handleDelete" />

        <!-- ============================================================= -->
        <!-- Modal -->
        <!-- ============================================================= -->

        <KnowledgeBaseModal :open="modalOpen" :knowledge-base="selectedKnowledgeBase" :processing="processing"
            :errors="errors" @close="closeModal" @save="handleSave" />

    </div>
</template>
