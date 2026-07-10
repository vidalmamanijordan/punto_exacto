<script setup lang="ts">
import { Check, ChevronLeft, ChevronRight, MapPin, Save, X } from '@lucide/vue';
import { computed, reactive, ref, watch } from 'vue';
import {
    DialogClose,
    DialogContent,
    DialogOverlay,
    DialogPortal,
    DialogRoot,
} from 'reka-ui';
import { toast } from 'vue-sonner';
import { Button } from '@/components/ui/button';
import type { Campus } from '@/modules/campus/types/campus';
import type { Category } from '@/modules/categories/types/category';
import type { Place, PlaceFormData } from '../types/place';
import { placeService } from '../services/place.service';
import PlaceForm from './PlaceForm.vue';

const props = defineProps<{
    open: boolean;
    place: Place | null;
    campuses: Campus[];
    categories: Category[];
}>();

const emit = defineEmits<{
    close: [];
    saved: [];
}>();

const isEditing = computed(() => !!props.place);
const currentStep = ref<1 | 2>(1);
const loading = ref(false);

const form = reactive<PlaceFormData>({
    campus_id: null,
    category_id: null,
    name: '',
    description: '',
    building: '',
    floor: '',
    room: '',
    image: '',
    latitude: null,
    longitude: null,
    is_active: true,
});

const errors = reactive({
    campus_id: '',
    category_id: '',
    name: '',
    description: '',
    building: '',
    floor: '',
    room: '',
    image: '',
    latitude: '',
    longitude: '',
});

const hasStep1Errors = computed(() =>
    !!(errors.campus_id || errors.category_id || errors.name || errors.description),
);

const hasStep2Errors = computed(() =>
    !!(errors.building || errors.floor || errors.room || errors.image || errors.latitude || errors.longitude),
);

const resetForm = () => {
    form.campus_id = null;
    form.category_id = null;
    form.name = '';
    form.description = '';
    form.building = '';
    form.floor = '';
    form.room = '';
    form.image = '';
    form.latitude = null;
    form.longitude = null;
    form.is_active = true;

    Object.keys(errors).forEach((key) => {
        errors[key as keyof typeof errors] = '';
    });
};

const handleClose = () => {
    if (loading.value) {
        return;
    }
    currentStep.value = 1;
    emit('close');
};

watch(
    () => props.place,
    (place) => {
        if (place) {
            form.campus_id = place.campus_id;
            form.category_id = place.category_id;
            form.name = place.name;
            form.description = place.description ?? '';
            form.building = place.building ?? '';
            form.floor = place.floor ?? '';
            form.room = place.room ?? '';
            form.image = place.image ?? '';
            form.latitude = place.latitude;
            form.longitude = place.longitude;
            form.is_active = place.is_active;
        } else {
            resetForm();
        }
    },
    { immediate: true },
);

watch(
    () => props.open,
    (isOpen) => {
        if (!isOpen) {
            currentStep.value = 1;
        }
    },
);

const savePlace = async () => {
    try {
        loading.value = true;

        Object.keys(errors).forEach((key) => {
            errors[key as keyof typeof errors] = '';
        });

        if (isEditing.value && props.place) {
            await placeService.update(props.place.id, form);

            toast.success('Lugar actualizado', {
                description: `"${form.name}" fue actualizado exitosamente.`,
            });
        } else {
            await placeService.create(form);

            toast.success('Lugar creado', {
                description: `"${form.name}" fue registrado exitosamente.`,
            });
        }

        resetForm();
        emit('saved');
        emit('close');
    } catch (error: any) {
        if (error.response?.status === 422) {
            const validationErrors = error.response.data.errors;

            Object.keys(errors).forEach((key) => {
                errors[key as keyof typeof errors] = validationErrors[key]?.[0] ?? '';
            });

            toast.error('Formulario inválido', {
                description: 'Revisa los campos marcados y corrígelos.',
            });

            if (hasStep1Errors.value) {
                currentStep.value = 1;
            }

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
    <DialogRoot :open="open" @update:open="(val) => !val && handleClose()">
        <DialogPortal>
            <DialogOverlay
                class="data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 fixed inset-0 z-50 bg-black/30 backdrop-blur-sm duration-200"
            />
            <DialogContent
                class="bg-background data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 fixed top-[50%] left-[50%] z-50 w-full max-w-[calc(100%-2rem)] translate-x-[-50%] translate-y-[-50%] rounded-xl border bg-white shadow-xl duration-200 sm:max-w-3xl"
            >
                <!-- Header -->
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100">
                            <MapPin class="h-4 w-4 text-slate-600" />
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-800">
                                {{ isEditing ? 'Editar Lugar' : 'Nuevo Lugar' }}
                            </h2>
                            <p class="text-xs text-slate-400">
                                {{ isEditing ? 'Modifica la información del lugar' : 'Completa la información en 2 pasos' }}
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

                <!-- Step indicator -->
                <div class="border-b border-slate-100 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <!-- Paso 1 -->
                        <button
                            type="button"
                            class="flex items-center gap-2.5 transition-opacity"
                            :class="currentStep === 1 ? 'opacity-100' : 'opacity-60 hover:opacity-80'"
                            @click="currentStep = 1"
                        >
                            <div
                                :class="[
                                    'flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-xs font-semibold transition-all',
                                    currentStep === 1
                                        ? 'bg-slate-900 text-white shadow-sm'
                                        : 'bg-emerald-100 text-emerald-600',
                                ]"
                            >
                                <Check v-if="currentStep > 1 && !hasStep1Errors" class="h-3.5 w-3.5" />
                                <span v-else>1</span>
                            </div>
                            <div class="text-left">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Paso 1</p>
                                <p :class="['text-sm font-semibold', currentStep === 1 ? 'text-slate-800' : 'text-slate-500']">
                                    Información general
                                </p>
                            </div>
                            <span
                                v-if="hasStep1Errors"
                                class="h-2 w-2 rounded-full bg-destructive"
                                title="Hay errores en este paso"
                            />
                        </button>

                        <!-- Connector -->
                        <div
                            :class="[
                                'h-px flex-1 transition-all duration-300',
                                currentStep > 1 && !hasStep1Errors ? 'bg-emerald-200' : 'bg-slate-200',
                            ]"
                        />

                        <!-- Paso 2 -->
                        <button
                            type="button"
                            class="flex items-center gap-2.5 transition-opacity"
                            :class="currentStep === 2 ? 'opacity-100' : 'opacity-60 hover:opacity-80'"
                            @click="currentStep = 2"
                        >
                            <div
                                :class="[
                                    'flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-xs font-semibold transition-all',
                                    currentStep === 2
                                        ? 'bg-slate-900 text-white shadow-sm'
                                        : 'bg-slate-100 text-slate-400',
                                ]"
                            >
                                2
                            </div>
                            <div class="text-left">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Paso 2</p>
                                <p :class="['text-sm font-semibold', currentStep === 2 ? 'text-slate-800' : 'text-slate-500']">
                                    Ubicación y detalles
                                </p>
                            </div>
                            <span
                                v-if="hasStep2Errors"
                                class="h-2 w-2 rounded-full bg-destructive"
                                title="Hay errores en este paso"
                            />
                        </button>
                    </div>
                </div>

                <!-- Form content -->
                <div class="px-6 py-5">
                    <PlaceForm
                        :form="form"
                        :campuses="props.campuses"
                        :categories="props.categories"
                        :errors="errors"
                        :step="currentStep"
                    />
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

                    <div class="flex items-center gap-2">
                        <Button
                            v-if="currentStep === 2"
                            variant="outline"
                            :disabled="loading"
                            class="gap-1.5"
                            @click="currentStep = 1"
                        >
                            <ChevronLeft class="h-4 w-4" />
                            Anterior
                        </Button>

                        <Button
                            v-if="currentStep === 1"
                            class="gap-1.5 bg-slate-900 text-white hover:bg-slate-700"
                            @click="currentStep = 2"
                        >
                            Siguiente
                            <ChevronRight class="h-4 w-4" />
                        </Button>

                        <Button
                            v-if="currentStep === 2"
                            :disabled="loading"
                            class="gap-1.5 bg-slate-900 text-white hover:bg-slate-700"
                            @click="savePlace"
                        >
                            <span
                                v-if="loading"
                                class="h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent"
                            />
                            <Save v-else class="h-4 w-4" />
                            {{ loading ? 'Guardando...' : isEditing ? 'Actualizar' : 'Guardar' }}
                        </Button>
                    </div>
                </div>
            </DialogContent>
        </DialogPortal>
    </DialogRoot>
</template>
