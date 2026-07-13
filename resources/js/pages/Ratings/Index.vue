<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Plus } from '@lucide/vue';
import { toast } from 'vue-sonner';
import { Button } from '@/components/ui/button';
import ConfirmDialog from '@/components/shared/ConfirmDialog.vue';
import RatingTable from '@/modules/ratings/components/RatingTable.vue';
import RatingModal from '@/modules/ratings/components/RatingModal.vue';
import { ratingService } from '@/modules/ratings/services/rating.service';
import type { Rating } from '@/modules/ratings/types/rating';

// =====================
// STATE
// =====================

const ratings = ref<Rating[]>([]);
const loading = ref(false);

// =====================
// LOAD DATA
// =====================

const loadRatings = async () => {
    try {
        loading.value = true;

        const response = await ratingService.getAll();

        ratings.value = response.data;

    } finally {
        loading.value = false;
    }
};

// =====================
// MODAL
// =====================

const showModal = ref(false);

const selectedRating = ref<Rating | null>(null);

const openCreate = () => {
    selectedRating.value = null;
    showModal.value = true;
};

const openEdit = (rating: Rating) => {
    selectedRating.value = rating;
    showModal.value = true;
};

// =====================
// DELETE
// =====================

const showDeleteDialog = ref(false);

const ratingToDelete = ref<Rating | null>(null);

const deleteLoading = ref(false);

const openDelete = (rating: Rating) => {
    ratingToDelete.value = rating;
    showDeleteDialog.value = true;
};

const deleteRating = async () => {

    if (!ratingToDelete.value) return;

    try {

        deleteLoading.value = true;

        await ratingService.delete(ratingToDelete.value.id);

        toast.success('Valoración eliminada', {
            description: 'La valoración fue eliminada correctamente.',
        });

        showDeleteDialog.value = false;
        ratingToDelete.value = null;

        await loadRatings();

    } catch (error) {

        console.error(error);

        toast.error('No se pudo eliminar', {
            description: 'Ocurrió un error inesperado.',
        });

    } finally {

        deleteLoading.value = false;

    }

};

// =====================
// CALLBACK
// =====================

const onSaved = async () => {
    await loadRatings();
};

onMounted(() => {
    loadRatings();
});
</script>

<template>

    <Head title="Valoraciones" />

    <div class="p-6">

        <!-- Header -->

        <div class="mb-6 flex items-center justify-between">

            <div>

                <h1 class="text-xl font-semibold text-slate-800">
                    Gestión de Valoraciones
                </h1>

                <p class="text-sm text-slate-400">
                    Administra las valoraciones realizadas por los usuarios.
                </p>

            </div>

            <Button size="sm" class="gap-2" @click="openCreate">
                <Plus class="h-4 w-4" />

                Nueva Valoración
            </Button>

        </div>

        <!-- Tabla -->

        <RatingTable :ratings="ratings" :loading="loading" @edit="openEdit" @delete="openDelete" />

        <!-- Modal -->

        <RatingModal :open="showModal" :rating="selectedRating" @close="showModal = false" @saved="onSaved" />

        <!-- Confirm -->

        <ConfirmDialog :open="showDeleteDialog" title="Eliminar valoración"
            :message="`¿Deseas eliminar la valoración realizada por '${ratingToDelete?.user.name}'?`"
            :loading="deleteLoading" variant="destructive" confirm-label="Eliminar"
            :confirmation-text="ratingToDelete?.user.name" @close="showDeleteDialog = false" @confirm="deleteRating" />

    </div>

</template>
