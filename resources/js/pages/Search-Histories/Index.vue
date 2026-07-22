<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Plus } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { toast } from 'vue-sonner';
import SearchHistoryTable from '@/modules/search-histories/components/SearchHistoryTable.vue';
import SearchHistoryModal from '@/modules/search-histories/components/SearchHistoryModal.vue';
import ConfirmDialog from '@/components/shared/ConfirmDialog.vue';
import { searchHistoryService } from '@/modules/search-histories/services/searchHistory.service';
import { userService } from '@/modules/search-histories/services/user.service';
import { placeService } from '@/modules/search-histories/services/place.service';
import type { SearchHistory } from '@/modules/search-histories/types/search-history';
import type { User } from '@/modules/search-histories/types/user';
import type { Place } from '@/modules/search-histories/types/place';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Historial de Búsquedas',
                href: '/search-histories',
            },
        ],
    },
});

const searchHistories = ref<SearchHistory[]>([]);
const users = ref<User[]>([]);
const places = ref<Place[]>([]);

const loadSearchHistories = async () => {
    try {
        const response = await searchHistoryService.getAll();
        searchHistories.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

const loadUsers = async () => {
    try {
        const response = await userService.getAll();
        users.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

const loadPlaces = async () => {
    try {
        const response = await placeService.getAll();
        places.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

const loadData = async () => {
    await Promise.all([
        loadSearchHistories(),
        loadUsers(),
        loadPlaces(),
    ]);
};

const showModal = ref(false);

const selectedSearchHistory = ref<SearchHistory | null>(null);

const openCreate = () => {
    selectedSearchHistory.value = null;
    showModal.value = true;
};

const openEdit = (searchHistory: SearchHistory) => {
    selectedSearchHistory.value = searchHistory;
    showModal.value = true;
};

const showDeleteDialog = ref(false);

const searchHistoryToDelete = ref<SearchHistory | null>(null);

const openDelete = (searchHistory: SearchHistory) => {
    searchHistoryToDelete.value = searchHistory;
    showDeleteDialog.value = true;
};

const deleteLoading = ref(false);

const deleteSearchHistory = async () => {
    if (!searchHistoryToDelete.value) {
        return;
    }

    try {
        deleteLoading.value = true;

        await searchHistoryService.delete(searchHistoryToDelete.value.id);

        toast.success('Historial eliminado', {
            description: 'El historial fue eliminado correctamente.',
        });

        showDeleteDialog.value = false;
        searchHistoryToDelete.value = null;

        await loadSearchHistories();
    } catch (error) {
        console.error(error);

        toast.error('No se pudo eliminar', {
            description: 'Ocurrió un error inesperado.',
        });
    } finally {
        deleteLoading.value = false;
    }
};

onMounted(() => {
    loadData();
});
</script>

<template>

    <Head title="Historial de Búsquedas" />

    <div class="p-6">

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-slate-800">
                    Gestión de Historial de Búsquedas
                </h1>

                <p class="mt-0.5 text-sm text-slate-400">
                    Administra los historiales de búsqueda realizados por los usuarios.
                </p>
            </div>

            <Button size="sm" class="gap-2" @click="openCreate">
                <Plus class="h-4 w-4" />

                Nuevo Historial
            </Button>
        </div>

        <SearchHistoryTable :search-histories="searchHistories" @edit="openEdit" @delete="openDelete" />

        <SearchHistoryModal
            :open="showModal"
            :search-history="selectedSearchHistory"
            :users="users"
            :places="places"
            @close="showModal = false"
            @saved="loadSearchHistories"
        />

        <ConfirmDialog
            :open="showDeleteDialog"
            title="Eliminar historial"
            :message="`¿Deseas eliminar el historial de '${searchHistoryToDelete?.user?.name}'?`"
            :loading="deleteLoading"
            variant="destructive"
            confirm-label="Eliminar"
            :confirmation-text="searchHistoryToDelete?.user?.name"
            @close="showDeleteDialog = false"
            @confirm="deleteSearchHistory"
        />

    </div>

</template>
