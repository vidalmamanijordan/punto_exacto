<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Plus } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { toast } from 'vue-sonner';
import PlaceTable from '@/modules/places/components/PlaceTable.vue';
import PlaceModal from '@/modules/places/components/PlaceModal.vue';
import ConfirmDialog from '@/components/shared/ConfirmDialog.vue';
import { placeService } from '@/modules/places/services/place.service';
import { campusService } from '@/modules/campus/services/campus.service';
import { categoryService } from '@/modules/categories/services/category.service';
import type { Place } from '@/modules/places/types/place';
import type { Campus } from '@/modules/campus/types/campus';
import type { Category } from '@/modules/categories/types/category';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Lugares',
                href: '/places',
            },
        ],
    },
});

const places = ref<Place[]>([]);
const campuses = ref<Campus[]>([]);
const categories = ref<Category[]>([]);

const loadPlaces = async () => {
    try {
        const response = await placeService.getAll();
        places.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

const loadCampuses = async () => {
    try {
        const response = await campusService.getAll();
        campuses.value = response.data;
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
        loadPlaces(),
        loadCampuses(),
        loadCategories(),
    ]);
};

const showModal = ref(false);

const selectedPlace =
    ref<Place | null>(null);

const openCreate = () => {
    selectedPlace.value = null;
    showModal.value = true;
};

const openEdit = (
    place: Place
) => {
    selectedPlace.value = place;
    showModal.value = true;
};

const showDeleteDialog =
    ref(false);

const placeToDelete =
    ref<Place | null>(null);

const openDelete = (
    place: Place
) => {
    placeToDelete.value = place;
    showDeleteDialog.value = true;
};

const deleteLoading = ref(false);

const deletePlace = async () => {
    if (!placeToDelete.value) {
        return;
    }

    try {
        deleteLoading.value = true;

        await placeService.delete(
            placeToDelete.value.id
        );

        toast.success(
            'Lugar eliminado',
            {
                description:
                    `"${placeToDelete.value.name}" fue eliminado correctamente.`,
            }
        );

        showDeleteDialog.value = false;
        placeToDelete.value = null;

        await loadPlaces();
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

    <Head title="Lugares" />

    <div class="p-6">

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-slate-800">
                    Gestión de Lugares
                </h1>

                <p class="mt-0.5 text-sm text-slate-400">
                    Administra los lugares de todos los campus.
                </p>
            </div>

            <Button size="sm" class="gap-2" @click="openCreate">
                <Plus class="h-4 w-4" />

                Nuevo Lugar
            </Button>
        </div>

        <PlaceTable :places="places" @edit="openEdit" @delete="openDelete" />

        <PlaceModal :open="showModal" :place="selectedPlace" :campuses="campuses" :categories="categories"
            @close="showModal = false" @saved="loadPlaces" />

        <ConfirmDialog :open="showDeleteDialog" title="Eliminar lugar"
            :message="`¿Deseas eliminar el lugar '${placeToDelete?.name}'?`" :loading="deleteLoading"
            variant="destructive" confirm-label="Eliminar" :confirmation-text="placeToDelete?.name"
            @close="showDeleteDialog = false" @confirm="deletePlace" />
    </div>
</template>
