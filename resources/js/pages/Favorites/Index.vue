<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Plus } from '@lucide/vue';
import { toast } from 'vue-sonner';
import { Button } from '@/components/ui/button';
import ConfirmDialog from '@/components/shared/ConfirmDialog.vue';
import FavoriteTable from '@/modules/favorites/components/FavoriteTable.vue';
import FavoriteModal from '@/modules/favorites/components/FavoriteModal.vue';
import { favoriteService } from '@/modules/favorites/services/favorite.service';
import type { Favorite } from '@/modules/favorites/types/favorite';

// =====================
// STATE
// =====================

const favorites = ref<Favorite[]>([]);
const loading = ref(false);

// =====================
// LOAD DATA
// =====================

const loadFavorites = async () => {

    try {

        loading.value = true;

        const response = await favoriteService.getAll();

        favorites.value = response.data;

    } finally {

        loading.value = false;

    }

};

// =====================
// MODAL
// =====================

const showModal = ref(false);

const selectedFavorite = ref<Favorite | null>(null);

const openCreate = () => {

    selectedFavorite.value = null;

    showModal.value = true;

};

const openEdit = (favorite: Favorite) => {

    selectedFavorite.value = favorite;

    showModal.value = true;

};

// =====================
// DELETE
// =====================

const showDeleteDialog = ref(false);

const favoriteToDelete = ref<Favorite | null>(null);

const deleteLoading = ref(false);

const openDelete = (favorite: Favorite) => {

    favoriteToDelete.value = favorite;

    showDeleteDialog.value = true;

};

const deleteFavorite = async () => {

    if (!favoriteToDelete.value) return;

    try {

        deleteLoading.value = true;

        await favoriteService.delete(favoriteToDelete.value.id);

        toast.success('Favorito eliminado', {
            description: 'El favorito fue eliminado correctamente.',
        });

        showDeleteDialog.value = false;

        favoriteToDelete.value = null;

        await loadFavorites();

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
// SAVE CALLBACK
// =====================

const onSaved = async () => {

    await loadFavorites();

};

onMounted(() => {

    loadFavorites();

});
</script>

<template>

    <Head title="Favoritos" />

    <div class="p-6">

        <!-- HEADER -->

        <div class="mb-6 flex items-center justify-between">

            <div>

                <h1 class="text-xl font-semibold text-slate-800">

                    Gestión de Favoritos

                </h1>

                <p class="text-sm text-slate-400">

                    Administra los lugares favoritos registrados por los usuarios.

                </p>

            </div>

            <Button size="sm" class="gap-2" @click="openCreate">

                <Plus class="h-4 w-4" />

                Nuevo Favorito

            </Button>

        </div>

        <!-- TABLE -->

        <FavoriteTable :favorites="favorites" :loading="loading" @edit="openEdit" @delete="openDelete" />

        <!-- MODAL -->

        <FavoriteModal :open="showModal" :favorite="selectedFavorite" @close="showModal = false" @saved="onSaved" />

        <!-- DELETE -->

        <ConfirmDialog :open="showDeleteDialog" title="Eliminar favorito"
            :message="`¿Deseas eliminar el favorito '${favoriteToDelete?.place.name}'?`"
            :loading="deleteLoading" variant="destructive" confirm-label="Eliminar"
            :confirmation-text="favoriteToDelete?.place.name" @close="showDeleteDialog = false"
            @confirm="deleteFavorite" />

    </div>

</template>