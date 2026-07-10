<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Plus } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { toast } from 'vue-sonner';
import CampusTable from '@/modules/campus/components/CampusTable.vue';
import CampusModal from '@/modules/campus/components/CampusModal.vue';
import ConfirmDialog from '@/components/shared/ConfirmDialog.vue';
import { campusService } from '@/modules/campus/services/campus.service';
import type { Campus } from '@/modules/campus/types/campus';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Campus',
                href: '/campus',
            },
        ],
    },
});

const campuses = ref<Campus[]>([]);

const loadCampuses = async () => {
    try {
        const response =
            await campusService.getAll();

        campuses.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

const showModal = ref(false);

const selectedCampus =
    ref<Campus | null>(null);

const openCreate = () => {
    selectedCampus.value = null;
    showModal.value = true;
};

const openEdit = (
    campus: Campus
) => {
    selectedCampus.value = campus;
    showModal.value = true;
};

const showDeleteDialog = ref(false);

const campusToDelete =
    ref<Campus | null>(null);

const openDelete = (
    campus: Campus
) => {
    campusToDelete.value = campus;
    showDeleteDialog.value = true;
};

const deleteLoading = ref(false);

const deleteCampus = async () => {
    if (!campusToDelete.value) {
        return;
    }

    try {
        deleteLoading.value = true;

        await campusService.delete(
            campusToDelete.value.id
        );

        toast.success('Campus eliminado', {
            description:
                `"${campusToDelete.value.name}" fue eliminado permanentemente.`,
        });

        showDeleteDialog.value = false;

        campusToDelete.value = null;

        await loadCampuses();
    } catch (error) {
        console.error(error);

        toast.error(
            'No se pudo eliminar',
            {
                description:
                    'Ocurrió un error inesperado. Inténtalo nuevamente.',
            }
        );
    } finally {
        deleteLoading.value = false;
    }
};

onMounted(() => {
    loadCampuses();
});
</script>

<template>

    <Head title="Campus" />

    <div class="p-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-slate-800">
                    Gestión de Campus
                </h1>

                <p class="mt-0.5 text-sm text-slate-400">
                    Administra los campus
                    universitarios disponibles
                    en la plataforma.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <Button size="sm"
                    class="gap-2 bg-slate-900 text-white shadow-sm transition-all duration-200 hover:bg-slate-700 active:scale-95"
                    @click="openCreate">
                    <Plus class="h-3.5 w-3.5" />

                    Nuevo Campus
                </Button>
            </div>
        </div>

        <CampusTable :campuses="campuses" @edit="openEdit" @delete="openDelete" />

        <CampusModal :open="showModal" :campus="selectedCampus" @close="showModal = false" @saved="loadCampuses" />

        <ConfirmDialog :open="showDeleteDialog" title="Eliminar campus"
            :message="`¿Deseas eliminar el campus '${campusToDelete?.name}'?`" :loading="deleteLoading"
            variant="destructive" confirm-label="Eliminar" :confirmation-text="campusToDelete?.name"
            @close="showDeleteDialog = false" @confirm="deleteCampus" />
    </div>
</template>