<script setup lang="ts">
import { Save, User, X } from '@lucide/vue';
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
import type { Role } from '@/modules/roles/types/role';
import type { User as AppUser } from '../types/user';
import { userService } from '../services/user.service';
import UserForm from './UserForm.vue';

const props = defineProps<{
    open: boolean;
    user: AppUser | null;
    roles: Role[];
}>();

const emit = defineEmits<{
    close: [];
    saved: [];
}>();

const isEditing = computed(() => !!props.user);
const loading = ref(false);

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '',
    is_active: true,
});

const errors = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '',
});

const resetForm = () => {
    form.name = '';
    form.email = '';
    form.password = '';
    form.password_confirmation = '';
    form.role = '';
    form.is_active = true;

    Object.keys(errors).forEach((key) => {
        errors[key as keyof typeof errors] = '';
    });
};

const handleClose = () => {
    if (loading.value) {
        return;
    }
    emit('close');
};

watch(
    () => props.user,
    (user) => {
        if (user) {
            form.name = user.name;
            form.email = user.email;
            form.password = '';
            form.password_confirmation = '';
            form.role = user.roles?.[0]?.name ?? '';
            form.is_active = user.is_active;
        } else {
            resetForm();
        }
    },
    { immediate: true },
);

const saveUser = async () => {
    try {
        loading.value = true;

        Object.keys(errors).forEach((key) => {
            errors[key as keyof typeof errors] = '';
        });

        if (isEditing.value && props.user) {
            await userService.update(props.user.id, form);

            toast.success('Usuario actualizado', {
                description: `"${form.name}" fue actualizado correctamente.`,
            });
        } else {
            await userService.create(form);

            toast.success('Usuario registrado', {
                description: `"${form.name}" fue registrado correctamente.`,
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
                            <User class="h-4 w-4 text-slate-600" />
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-800">
                                {{ isEditing ? 'Editar Usuario' : 'Nuevo Usuario' }}
                            </h2>
                            <p class="text-xs text-slate-400">
                                {{ isEditing ? 'Actualiza la información del usuario.' : 'Completa la información del nuevo usuario.' }}
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
                    <UserForm :form="form" :roles="roles" :errors="errors" :is-editing="isEditing" />
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
                        @click="saveUser"
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
