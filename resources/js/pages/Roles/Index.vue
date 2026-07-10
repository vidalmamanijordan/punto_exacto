<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Plus } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { toast } from 'vue-sonner';
import RoleTable from '@/modules/roles/components/RoleTable.vue';
import RoleModal from '@/modules/roles/components/RoleModal.vue';
import ConfirmDialog from '@/components/shared/ConfirmDialog.vue';
import { roleService } from '@/modules/roles/services/role.service';
import { permissionService } from '@/modules/roles/services/permission.service';
import type { Role } from '@/modules/roles/types/role';
import type { Permission } from '@/modules/roles/types/permission';

// =====================
// STATE
// =====================
const roles = ref<Role[]>([]);
const permissions = ref<Permission[]>([]);

// =====================
// LOAD DATA
// =====================
const loadRoles = async () => {
    const response = await roleService.getAll();
    roles.value = response.data;
};

const loadPermissions = async () => {
    const response = await permissionService.getAll();
    permissions.value = response.data;
};

const loadData = async () => {
    await Promise.all([
        loadRoles(),
        loadPermissions(),
    ]);
};

// =====================
// MODAL
// =====================
const showModal = ref(false);
const selectedRole = ref<Role | null>(null);

const openCreate = () => {
    selectedRole.value = null;
    showModal.value = true;
};

const openEdit = (role: Role) => {
    selectedRole.value = role;
    showModal.value = true;
};

// =====================
// DELETE
// =====================
const showDeleteDialog = ref(false);
const roleToDelete = ref<Role | null>(null);
const deleteLoading = ref(false);

const openDelete = (role: Role) => {
    roleToDelete.value = role;
    showDeleteDialog.value = true;
};

const deleteRole = async () => {
    if (!roleToDelete.value) return;

    try {
        deleteLoading.value = true;

        await roleService.delete(roleToDelete.value.id);

        toast.success('Rol eliminado', {
            description: `"${roleToDelete.value.name}" fue eliminado correctamente.`,
        });

        showDeleteDialog.value = false;
        roleToDelete.value = null;

        await loadRoles();

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
    await loadRoles();
};

onMounted(() => {
    loadData();
});
</script>

<template>

    <Head title="Roles" />

    <div class="p-6">

        <!-- HEADER -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-slate-800">
                    Gestión de Roles
                </h1>

                <p class="text-sm text-slate-400">
                    Administra los roles y permisos del sistema.
                </p>
            </div>

            <Button size="sm" class="gap-2" @click="openCreate">
                <Plus class="h-4 w-4" />
                Nuevo Rol
            </Button>
        </div>

        <!-- TABLE -->
        <RoleTable :roles="roles" @edit="openEdit" @delete="openDelete" />

        <!-- MODAL -->
        <RoleModal :open="showModal" :role="selectedRole" :permissions="permissions" @close="showModal = false"
            @saved="onSaved" />

        <!-- DELETE -->
        <ConfirmDialog :open="showDeleteDialog" title="Eliminar rol"
            :message="`¿Deseas eliminar el rol '${roleToDelete?.name}'?`" :loading="deleteLoading" variant="destructive"
            confirm-label="Eliminar" :confirmation-text="roleToDelete?.name" @close="showDeleteDialog = false"
            @confirm="deleteRole" />
    </div>
</template>
