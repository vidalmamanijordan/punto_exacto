<script setup lang="ts">
import {
    reactive,
    ref,
    watch,
    computed,
} from 'vue';
import { toast } from 'vue-sonner';
import {
    Shield,
    Save,
    X,
} from '@lucide/vue';

import {
    DialogClose,
    DialogContent,
    DialogOverlay,
    DialogPortal,
    DialogRoot,
} from 'reka-ui';

import { Button } from '@/components/ui/button';

import RoleForm from './RoleForm.vue';
import { roleService } from '../services/role.service';

import type { Role } from '../types/role';
import type { Permission } from '../types/permission';

const props = defineProps<{
    open: boolean;
    role: Role | null;
    permissions: Permission[];
}>();

const emit = defineEmits<{
    close: [];
    saved: [];
}>();

const isEditing = computed(
    () => !!props.role
);

const form = reactive({
    name: '',
    permissions: [] as string[],
});

const errors = reactive({
    name: '',
    permissions: '',
});

const loading = ref(false);

const resetForm = () => {
    form.name = '';
    form.permissions = [];

    errors.name = '';
    errors.permissions = '';
};

const handleClose = () => {
    if (loading.value) {
        return;
    }

    emit('close');
};

watch(
    () => props.role,
    (role) => {
        if (role) {
            form.name = role.name;

            form.permissions =
                role.permissions?.map(
                    permission => permission.name
                ) ?? [];
        } else {
            resetForm();
        }
    },
    { immediate: true }
);

const saveRole = async () => {
    try {
        loading.value = true;

        errors.name = '';
        errors.permissions = '';

        if (
            isEditing.value &&
            props.role
        ) {
            await roleService.update(
                props.role.id,
                {
                    name: form.name,
                    permissions:
                        form.permissions,
                }
            );

            toast.success(
                'Rol actualizado',
                {
                    description:
                        `"${form.name}" fue actualizado exitosamente.`,
                }
            );
        } else {
            await roleService.create({
                name: form.name,
                permissions:
                    form.permissions,
            });

            toast.success(
                'Rol creado',
                {
                    description:
                        `"${form.name}" fue registrado exitosamente.`,
                }
            );
        }

        resetForm();

        emit('saved');
        emit('close');

    } catch (error: any) {

        if (
            error.response?.status === 422
        ) {
            const validationErrors =
                error.response.data.errors;

            errors.name =
                validationErrors.name?.[0] ??
                '';

            errors.permissions =
                validationErrors.permissions?.[0] ??
                '';

            toast.error(
                'Formulario inválido',
                {
                    description:
                        'Revisa los campos marcados antes de continuar.',
                }
            );

            return;
        }

        console.error(error);

        toast.error(
            'No se pudo guardar',
            {
                description:
                    'Ocurrió un error inesperado.',
            }
        );

    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <DialogRoot :open="open" @update:open="
        (val) =>
            !val &&
            handleClose()
    ">
        <DialogPortal>
            <DialogOverlay
                class="data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 fixed inset-0 z-50 bg-black/30 backdrop-blur-sm duration-200" />

            <DialogContent
                class="bg-background data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 fixed top-[50%] left-[50%] z-50 flex w-full max-w-[calc(100%-2rem)] translate-x-[-50%] translate-y-[-50%] flex-col rounded-xl border bg-white shadow-xl duration-200 sm:max-w-4xl max-h-[90vh]">
                <!-- Header -->
                <div class="flex shrink-0 items-center justify-between border-b border-slate-100 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100">
                            <Shield class="h-4 w-4 text-slate-600" />
                        </div>

                        <div>
                            <h2 class="text-base font-semibold text-slate-800">
                                {{
                                    isEditing
                                        ? 'Editar Rol'
                                        : 'Nuevo Rol'
                                }}
                            </h2>

                            <p class="text-xs text-slate-400">
                                {{
                                    isEditing
                                        ? 'Modifica la información del rol y sus permisos.'
                                        : 'Completa la información para registrar un nuevo rol.'
                                }}
                            </p>
                        </div>
                    </div>

                    <DialogClose v-if="!loading"
                        class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600">
                        <X class="h-4 w-4" />
                        <span class="sr-only">
                            Cerrar
                        </span>
                    </DialogClose>
                </div>

                <!-- Nombre del rol (fijo, siempre visible) -->
                <div class="shrink-0 border-b border-slate-100 px-6 py-4">
                    <label class="text-sm text-slate-600">
                        Nombre del rol
                    </label>

                    <input v-model="form.name" type="text"
                        class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none" />

                    <p v-if="errors.name" class="mt-1 text-xs text-red-500">
                        {{ errors.name }}
                    </p>
                </div>

                <!-- Permisos (scrolleable) -->
                <div class="min-h-0 flex-1 overflow-y-auto px-6 py-5">
                    <RoleForm :form="form" :permissions="permissions" :errors="errors" />
                </div>

                <!-- Footer -->
                <div class="flex shrink-0 items-center justify-between border-t border-slate-100 px-6 py-4">
                    <Button variant="ghost" :disabled="loading
                        " class="text-slate-500" @click="
                            handleClose
                        ">
                        Cancelar
                    </Button>

                    <Button :disabled="loading
                        " class="gap-1.5 bg-slate-900 text-white hover:bg-slate-700" @click="
                            saveRole
                        ">
                        <span v-if="
                            loading
                        " class="h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent" />

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
