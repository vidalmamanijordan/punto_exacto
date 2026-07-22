<script setup lang="ts">
import { computed } from 'vue';
import AppCombobox from '@/components/shared/AppCombobox.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { Role } from '@/modules/roles/types/role';

const props = defineProps<{
    form: {
        name: string;
        email: string;
        password: string;
        password_confirmation: string;
        role: string;
        is_active: boolean;
    };
    roles: Role[];
    errors: Record<string, string>;
    isEditing: boolean;
}>();

const selectedRoleId = computed({
    get: () => props.roles.find((r) => r.name === props.form.role)?.id ?? null,
    set: (id: number | null) => {
        props.form.role = props.roles.find((r) => r.id === id)?.name ?? '';
    },
});
</script>

<template>
    <div class="space-y-4">
        <!-- Nombre -->
        <div class="space-y-1.5">
            <Label for="user-name">Nombre</Label>
            <Input id="user-name" v-model="form.name" placeholder="Ingrese el nombre completo"
                :aria-invalid="!!errors.name" />
            <p v-if="errors.name" class="text-xs text-destructive">{{ errors.name }}</p>
        </div>

        <!-- Email -->
        <div class="space-y-1.5">
            <Label for="user-email">Correo electrónico</Label>
            <Input id="user-email" v-model="form.email" type="email" placeholder="correo@ejemplo.com"
                :aria-invalid="!!errors.email" />
            <p v-if="errors.email" class="text-xs text-destructive">{{ errors.email }}</p>
        </div>

        <!-- Rol -->
        <div class="space-y-1.5">
            <Label>Rol</Label>
            <AppCombobox v-model="selectedRoleId" :options="roles" placeholder="Buscar rol..."
                empty-text="No se encontraron roles" />
            <p v-if="errors.role" class="text-xs text-destructive">{{ errors.role }}</p>
        </div>

        <!-- Contraseñas -->
        <div v-if="!isEditing" class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <Label for="user-password">Contraseña</Label>
                <Input id="user-password" v-model="form.password" type="password" :aria-invalid="!!errors.password" />
                <p v-if="errors.password" class="text-xs text-destructive">{{ errors.password }}</p>
            </div>

            <div class="space-y-1.5">
                <Label for="user-password-confirmation">Confirmar contraseña</Label>
                <Input id="user-password-confirmation" v-model="form.password_confirmation" type="password"
                    :aria-invalid="!!errors.password_confirmation" />
                <p v-if="errors.password_confirmation" class="text-xs text-destructive">{{ errors.password_confirmation
                    }}</p>
            </div>
        </div>

        <!-- Estado -->
        <div class="flex items-center gap-3 rounded-lg border border-slate-100 bg-slate-50/60 px-4 py-3">
            <input id="is_active" v-model="form.is_active" type="checkbox"
                class="h-4 w-4 rounded border-slate-300 accent-slate-900" />
            <div>
                <label for="is_active" class="cursor-pointer text-sm font-medium text-slate-700">
                    Usuario activo
                </label>
                <p class="text-xs text-slate-400">El usuario podrá acceder al sistema</p>
            </div>
        </div>
    </div>
</template>
