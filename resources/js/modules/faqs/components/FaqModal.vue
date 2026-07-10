<script setup lang="ts">
import { reactive, ref, watch, computed } from 'vue';
import { toast } from 'vue-sonner';
import { HelpCircle, Save, X } from '@lucide/vue';
import {
    DialogClose,
    DialogContent,
    DialogOverlay,
    DialogPortal,
    DialogRoot,
} from 'reka-ui';
import { Button } from '@/components/ui/button';
import FaqForm from './FaqForm.vue';
import { faqService } from '../services/faq.service';
import type {
    Faq,
    FaqFormData,
} from '../types/faq';
import type { Category } from '@/modules/categories/types/category';

const props = defineProps<{
    open: boolean;
    faq: Faq | null;
    categories: Category[];
}>();

const emit = defineEmits<{
    close: [];
    saved: [];
}>();

const isEditing = computed(
    () => !!props.faq
);

const form = reactive<FaqFormData>({
    category_id: null,
    question: '',
    answer: '',
    is_active: true,
});

const errors = reactive({
    category_id: '',
    question: '',
    answer: '',
});

const loading = ref(false);

const resetForm = () => {
    form.category_id = null;
    form.question = '';
    form.answer = '';
    form.is_active = true;
    errors.category_id = '';
    errors.question = '';
    errors.answer = '';
};

const handleClose = () => {
    if (loading.value) {
        return;
    }
    emit('close');
};

watch(
    () => props.faq,
    (faq) => {
        if (faq) {
            form.category_id = faq.category_id;
            form.question = faq.question;
            form.answer = faq.answer;
            form.is_active = faq.is_active;
        } else {
            resetForm();
        }
    },
    { immediate: true }
);

const saveFaq = async () => {
    try {
        loading.value = true;
        errors.category_id = '';
        errors.question = '';
        errors.answer = '';

        if (isEditing.value && props.faq) {
            await faqService.update(
                props.faq.id,
                form
            );
            toast.success(
                'FAQ actualizada',
                {
                    description:
                        `"${form.question}" fue actualizada exitosamente.`,
                }
            );
        } else {
            await faqService.create(
                form
            );
            toast.success(
                'FAQ creada',
                {
                    description:
                        `"${form.question}" fue registrada exitosamente.`,
                }
            );
        }
        resetForm();
        emit('saved');
        emit('close');
    } catch (error: any) {
        if (error.response?.status === 422) {
            const validationErrors =
                error.response.data.errors;
            errors.category_id =
                validationErrors.category_id?.[0] ?? '';
            errors.question =
                validationErrors.question?.[0] ?? '';
            errors.answer =
                validationErrors.answer?.[0] ?? '';
            toast.error(
                'Formulario inválido',
                {
                    description:
                        'Revisa los campos marcados y corrígelos antes de continuar.',
                }
            );
            return;
        }
        console.error(error);
        toast.error(
            'No se pudo guardar',
            {
                description:
                    'Ocurrió un error inesperado. Inténtalo nuevamente.',
            }
        );
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
                            <HelpCircle class="h-4 w-4 text-slate-600" />
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-800">
                                {{ isEditing ? 'Editar FAQ' : 'Nueva FAQ' }}
                            </h2>
                            <p class="text-xs text-slate-400">
                                {{ isEditing ? 'Modifica la información de la pregunta frecuente' : 'Completa la información de la pregunta frecuente' }}
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
                    <FaqForm :form="form" :categories="categories" :errors="errors" />
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
                        @click="saveFaq"
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
