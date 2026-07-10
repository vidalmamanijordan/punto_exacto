<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Plus } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import CategoryTable from '@/modules/categories/components/CategoryTable.vue';
import { categoryService } from '@/modules/categories/services/category.service';
import type { Category } from '@/modules/categories/types/category';
import CategoryModal from '@/modules/categories/components/CategoryModal.vue';
import { toast } from 'vue-sonner';
import ConfirmDialog from '@/components/shared/ConfirmDialog.vue';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Categorías',
                href: '/categories',
            },
        ],
    },
});

const categories = ref<Category[]>([]);

const loadCategories = async () => {
    try {
        const response = await categoryService.getAll();

        categories.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

const openCreate = () => {
    selectedCategory.value = null;
    showModal.value = true;
};

const openEdit = (category: Category) => {
    selectedCategory.value = category;
    showModal.value = true;
};

const showModal = ref(false);
const selectedCategory = ref<Category | null>(null);
const showDeleteDialog = ref(false);
const categoryToDelete = ref<Category | null>(null);

const openDelete = (
    category: Category
) => {
    categoryToDelete.value = category;
    showDeleteDialog.value = true;
};

const deleteLoading = ref(false);

const deleteCategory = async () => {
    if (!categoryToDelete.value) {
        return;
    }

    try {
        deleteLoading.value = true;

        await categoryService.delete(
            categoryToDelete.value.id
        );

        toast.success('Categoría eliminada', {
            description: `"${categoryToDelete.value?.name}" fue eliminada permanentemente.`,
        });

        showDeleteDialog.value = false;

        categoryToDelete.value = null;

        await loadCategories();
    } catch (error) {
        console.error(error);

        toast.error('No se pudo eliminar', {
            description: 'Ocurrió un error inesperado. Inténtalo de nuevo.',
        });
    } finally {
        deleteLoading.value = false;
    }
};

onMounted(() => {
    loadCategories();
});
</script>

<template>

    <Head title="Categorías" />

    <div class="p-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-slate-800">
                    Gestión de Categorías
                </h1>
                <p class="mt-0.5 text-sm text-slate-400">
                    Administra las categorías disponibles en la plataforma.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <Button size="sm"
                    class="gap-2 bg-slate-900 text-white shadow-sm transition-all duration-200 hover:bg-slate-700 active:scale-95"
                    @click="openCreate">
                    <Plus class="h-3.5 w-3.5" />
                    Nueva Categoría
                </Button>
            </div>
        </div>

        <CategoryTable :categories="categories" @edit="openEdit" @delete="openDelete" />

        <CategoryModal :open="showModal" :category="selectedCategory" @close="showModal = false"
            @saved="loadCategories" />

        <ConfirmDialog :open="showDeleteDialog" title="Eliminar categoría"
            :message="`¿Deseas eliminar la categoría '${categoryToDelete?.name}'?`" :loading="deleteLoading"
            variant="destructive" confirm-label="Eliminar" :confirmation-text="categoryToDelete?.name"
            @close="showDeleteDialog = false" @confirm="deleteCategory" />
    </div>
</template>
