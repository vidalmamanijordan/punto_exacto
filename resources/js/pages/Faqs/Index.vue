<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Plus } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { toast } from 'vue-sonner';
import FaqTable from '@/modules/faqs/components/FaqTable.vue';
import FaqModal from '@/modules/faqs/components/FaqModal.vue';
import ConfirmDialog from '@/components/shared/ConfirmDialog.vue';
import { faqService } from '@/modules/faqs/services/faq.service';
import { categoryService } from '@/modules/categories/services/category.service';
import type { Faq } from '@/modules/faqs/types/faq';
import type { Category } from '@/modules/categories/types/category';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Preguntas Frecuentes',
                href: '/faqs',
            },
        ],
    },
});

const faqs = ref<Faq[]>([]);
const categories = ref<Category[]>([]);

const loadFaqs = async () => {
    try {
        const response = await faqService.getAll();
        faqs.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

const loadCategories = async () => {
    try {
        const response = await categoryService.getAll();
        categories.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

const loadData = async () => {
    await Promise.all([
        loadFaqs(),
        loadCategories(),
    ]);
};

const showModal = ref(false);

const selectedFaq =
    ref<Faq | null>(null);

const openCreate = () => {
    selectedFaq.value = null;
    showModal.value = true;
};

const openEdit = (
    faq: Faq
) => {
    selectedFaq.value = faq;
    showModal.value = true;
};

const showDeleteDialog =
    ref(false);

const faqToDelete =
    ref<Faq | null>(null);

const openDelete = (
    faq: Faq
) => {
    faqToDelete.value = faq;
    showDeleteDialog.value = true;
};

const deleteLoading = ref(false);

const deleteFaq = async () => {
    if (!faqToDelete.value) {
        return;
    }

    try {
        deleteLoading.value = true;

        await faqService.delete(
            faqToDelete.value.id
        );

        toast.success(
            'Pregunta eliminada',
            {
                description:
                    `"${faqToDelete.value.question}" fue eliminada correctamente.`,
            }
        );

        showDeleteDialog.value = false;
        faqToDelete.value = null;

        await loadFaqs();
    } catch (error) {
        console.error(error);

        toast.error(
            'No se pudo eliminar',
            {
                description:
                    'Ocurrió un error inesperado.',
            }
        );
    } finally {
        deleteLoading.value = false;
    }
};

onMounted(() => {
    loadData();
});
</script>

<template>

    <Head title="Preguntas Frecuentes" />

    <div class="p-6">

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-slate-800">
                    Gestión de Preguntas Frecuentes
                </h1>

                <p class="mt-0.5 text-sm text-slate-400">
                    Administra las preguntas y respuestas disponibles para los usuarios.
                </p>
            </div>

            <Button size="sm" class="gap-2" @click="openCreate">
                <Plus class="h-4 w-4" />

                Nueva Pregunta
            </Button>
        </div>

        <FaqTable :faqs="faqs" @edit="openEdit" @delete="openDelete" />

        <FaqModal :open="showModal" :faq="selectedFaq" :categories="categories" @close="showModal = false"
            @saved="loadFaqs" />

        <ConfirmDialog :open="showDeleteDialog" title="Eliminar pregunta frecuente"
            :message="`¿Deseas eliminar la pregunta '${faqToDelete?.question}'?`" :loading="deleteLoading"
            variant="destructive" confirm-label="Eliminar" :confirmation-text="faqToDelete?.question"
            @close="showDeleteDialog = false" @confirm="deleteFaq" />
    </div>
</template>
