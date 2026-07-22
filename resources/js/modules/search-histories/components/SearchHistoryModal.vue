<script setup lang="ts">
import { reactive, ref, watch, computed } from 'vue';
import { toast } from 'vue-sonner';
import { Search, Save, X } from '@lucide/vue';
import {
    DialogClose,
    DialogContent,
    DialogOverlay,
    DialogPortal,
    DialogRoot,
} from 'reka-ui';
import { Button } from '@/components/ui/button';
import SearchHistoryForm from './SearchHistoryForm.vue';
import { searchHistoryService } from '../services/searchHistory.service';
import type { SearchHistory } from '../types/search-history';
import type { User } from '../types/user';
import type { Place } from '../types/place';

const props = defineProps<{
    open: boolean;
    searchHistory: SearchHistory | null;
    users: User[];
    places: Place[];
}>();

const emit = defineEmits<{
    close: [];
    saved: [];
}>();

const isEditing = computed(() => !!props.searchHistory);

const form = reactive({
    user_id: null as number | null,
    place_id: null as number | null,
    search_text: '',
});

const errors = reactive({
    user_id: '',
    place_id: '',
    search_text: '',
});

const loading = ref(false);

const resetForm = () => {
    form.user_id = null;
    form.place_id = null;
    form.search_text = '';
    errors.user_id = '';
    errors.place_id = '';
    errors.search_text = '';
};

const handleClose = () => {
    if (loading.value) {
        return;
    }
    emit('close');
};

watch(
    () => props.searchHistory,
    (history) => {
        if (history) {
            form.user_id = history.user.id;
            form.place_id = history.place.id;
            form.search_text = history.search_text;
        } else {
            resetForm();
        }
    },
    { immediate: true }
);

const saveSearchHistory = async () => {
    try {
        loading.value = true;
        errors.user_id = '';
        errors.place_id = '';
        errors.search_text = '';

        if (isEditing.value && props.searchHistory) {
            await searchHistoryService.update(props.searchHistory.id, form);
            toast.success('Historial actualizado', {
                description: `El historial fue actualizado exitosamente.`,
            });
        } else {
            await searchHistoryService.create(form);
            toast.success('Historial registrado', {
                description: `El historial fue registrado exitosamente.`,
            });
        }
        resetForm();
        emit('saved');
        emit('close');
    } catch (error: any) {
        if (error.response?.status === 422) {
            const validationErrors = error.response.data.errors;
            errors.user_id = validationErrors.user_id?.[0] ?? '';
            errors.place_id = validationErrors.place_id?.[0] ?? '';
            errors.search_text = validationErrors.search_text?.[0] ?? '';
            toast.error('Formulario inválido', {
                description: 'Revisa los campos marcados y corrígelos antes de continuar.',
            });
            return;
        }
        console.error(error);
        toast.error('No se pudo guardar', {
            description: 'Ocurrió un error inesperado. Inténtalo nuevamente.',
        });
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <DialogRoot :open="open" @update:open="(val) => !val && handleClose()">
        <DialogPortal>
            <DialogOverlay
                class="data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 fixed inset-0 z-50 bg-black/30 backdrop-blur-sm duration-200"
            />
            <DialogContent
                class="bg-background data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 fixed top-[50%] left-[50%] z-50 w-full max-w-[calc(100%-2rem)] translate-x-[-50%] translate-y-[-50%] rounded-xl border bg-white shadow-xl duration-200 sm:max-w-2xl"
            >
                <!-- Header -->
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100">
                            <Search class="h-4 w-4 text-slate-600" />
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-800">
                                {{ isEditing ? 'Editar Historial' : 'Nuevo Historial' }}
                            </h2>
                            <p class="text-xs text-slate-400">
                                {{ isEditing ? 'Modifica la información del historial de búsqueda' : 'Completa la información del historial de búsqueda' }}
                            </p>
                        </div>
                    </div>

                    <DialogClose
                        v-if="!loading"
                        class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600"
                    >
                        <X class="h-4 w-4" />
                        <span class="sr-only">Cerrar</span>
                    </DialogClose>
                </div>

                <!-- Form content -->
                <div class="px-6 py-5">
                    <SearchHistoryForm :form="form" :users="users" :places="places" :errors="errors" />
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-between border-t border-slate-100 px-6 py-4">
                    <Button
                        variant="ghost"
                        :disabled="loading"
                        class="text-slate-500"
                        @click="handleClose"
                    >
                        Cancelar
                    </Button>

                    <Button
                        :disabled="loading"
                        class="gap-1.5 bg-slate-900 text-white hover:bg-slate-700"
                        @click="saveSearchHistory"
                    >
                        <span
                            v-if="loading"
                            class="h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent"
                        />
                        <Save v-else class="h-4 w-4" />
                        {{ loading ? 'Guardando...' : isEditing ? 'Actualizar' : 'Guardar' }}
                    </Button>
                </div>
            </DialogContent>
        </DialogPortal>
    </DialogRoot>
</template>
