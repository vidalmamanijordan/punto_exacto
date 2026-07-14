<script setup lang="ts">
import {
    reactive,
    ref,
    watch,
    computed,
    onMounted,
} from 'vue';
import { toast } from 'vue-sonner';
import {
    Heart,
    Save,
    X,
} from '@lucide/vue';
import {
    DialogRoot,
    DialogPortal,
    DialogOverlay,
    DialogContent,
    DialogClose,
} from 'reka-ui';
import { Button } from '@/components/ui/button';
import FavoriteForm from './FavoriteForm.vue';
import { favoriteService } from '../services/favorite.service';
import { userService } from '../services/user.service';
import { placeService } from '../services/place.service';
import type { Favorite } from '../types/favorite';
import type { User } from '../types/user';
import type { Place } from '../types/place';

const props = defineProps<{
    open: boolean;
    favorite: Favorite | null;
}>();

const emit = defineEmits<{
    close: [];
    saved: [];
}>();

const isEditing = computed(() => !!props.favorite);

const loading = ref(false);

const users = ref<User[]>([]);
const places = ref<Place[]>([]);

const form = reactive({
    user_id: null as number | null,
    place_id: null as number | null,
});

const errors = reactive({
    user_id: '',
    place_id: '',
});

const resetForm = () => {
    form.user_id = null;
    form.place_id = null;

    errors.user_id = '';
    errors.place_id = '';
};

watch(
    () => props.favorite,
    (favorite) => {
        if (favorite) {
            form.user_id = favorite.user.id;
            form.place_id = favorite.place.id;
        } else {
            resetForm();
        }
    },
    { immediate: true }
);

const loadUsers = async () => {
    const response = await userService.getAll();
    users.value = response.data;
};

const loadPlaces = async () => {
    const response = await placeService.getAll();
    places.value = response.data;
};

onMounted(async () => {
    await Promise.all([
        loadUsers(),
        loadPlaces(),
    ]);
});

const handleClose = () => {
    if (loading.value) return;

    emit('close');
};

const saveFavorite = async () => {

    try {

        loading.value = true;

        errors.user_id = '';
        errors.place_id = '';

        if (isEditing.value && props.favorite) {

            await favoriteService.update(
                props.favorite.id,
                {
                    ...form,
                }
            );

            toast.success('Favorito actualizado', {
                description: 'El favorito fue actualizado correctamente.',
            });

        } else {

            await favoriteService.create({
                ...form,
            });

            toast.success('Favorito registrado', {
                description: 'El favorito fue registrado correctamente.',
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

            toast.error('Formulario inválido', {
                description: 'Corrige los campos marcados.',
            });

            return;

        }

        console.error(error);

        toast.error('No se pudo guardar', {
            description: 'Ocurrió un error inesperado.',
        });

    } finally {

        loading.value = false;

    }

};
</script>

<template>
    <DialogRoot :open="open" @update:open="(value) => !value && handleClose()">
        <DialogPortal>

            <DialogOverlay class="fixed inset-0 z-50 bg-black/30 backdrop-blur-sm" />

            <DialogContent
                class="fixed left-1/2 top-1/2 z-50 w-full max-w-2xl -translate-x-1/2 -translate-y-1/2 rounded-xl border bg-white shadow-xl">

                <!-- Header -->

                <div class="flex items-center justify-between border-b px-6 py-4">

                    <div class="flex items-center gap-3">

                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100">

                            <Heart class="h-4 w-4 text-slate-600" />

                        </div>

                        <div>

                            <h2 class="text-base font-semibold text-slate-800">

                                {{
                                    isEditing
                                        ? 'Editar Favorito'
                                        : 'Nuevo Favorito'
                                }}

                            </h2>

                            <p class="text-xs text-slate-400">

                                Asigna un lugar favorito a un usuario.

                            </p>

                        </div>

                    </div>

                    <DialogClose v-if="!loading" class="rounded-lg p-1 hover:bg-slate-100">
                        <X class="h-4 w-4" />
                    </DialogClose>

                </div>

                <!-- Body -->

                <div class="px-6 py-5">

                    <FavoriteForm :form="form" :users="users" :places="places" :errors="errors" />

                </div>

                <!-- Footer -->

                <div class="flex justify-between border-t px-6 py-4">

                    <Button variant="ghost" :disabled="loading" @click="handleClose">
                        Cancelar
                    </Button>

                    <Button :disabled="loading" class="gap-2" @click="saveFavorite">

                        <span v-if="loading"
                            class="h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent" />

                        <Save v-else class="h-4 w-4" />

                        {{
                            loading
                                ? 'Guardando...'
                                : isEditing
                                    ? 'Actualizar'
                                    : 'Guardar'
                        }}

                    </Button>

                </div>

            </DialogContent>

        </DialogPortal>
    </DialogRoot>
</template>