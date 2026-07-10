<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Plus } from '@lucide/vue';
import { toast } from 'vue-sonner';
import { Button } from '@/components/ui/button';
import ConfirmDialog from '@/components/shared/ConfirmDialog.vue';
import UserTable from '@/modules/users/components/UserTable.vue';
import UserModal from '@/modules/users/components/UserModal.vue';
import { userService } from '@/modules/users/services/user.service';
import { roleService } from '@/modules/users/services/role.service';
import type { User } from '@/modules/users/types/user';
import type { Role } from '@/modules/users/types/role';

// =====================
// STATE
// =====================

const users = ref<User[]>([]);
const roles = ref<Role[]>([]);

// =====================
// LOAD DATA
// =====================

const loadUsers = async () => {
    const response = await userService.getAll();
    users.value = response.data;
};

const loadRoles = async () => {
    const response = await roleService.getAll();
    roles.value = response.data;
};

const loadData = async () => {
    await Promise.all([
        loadUsers(),
        loadRoles(),
    ]);
};

// =====================
// MODAL
// =====================

const showModal = ref(false);

const selectedUser = ref<User | null>(null);

const openCreate = () => {
    selectedUser.value = null;
    showModal.value = true;
};

const openEdit = (user: User) => {
    selectedUser.value = user;
    showModal.value = true;
};

// =====================
// DELETE
// =====================

const showDeleteDialog = ref(false);

const userToDelete = ref<User | null>(null);

const deleteLoading = ref(false);

const openDelete = (user: User) => {
    userToDelete.value = user;
    showDeleteDialog.value = true;
};

const deleteUser = async () => {
    if (!userToDelete.value) return;

    try {

        deleteLoading.value = true;

        await userService.delete(userToDelete.value.id);

        toast.success('Usuario eliminado', {
            description: `"${userToDelete.value.name}" fue eliminado correctamente.`,
        });

        showDeleteDialog.value = false;
        userToDelete.value = null;

        await loadUsers();

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
    await loadUsers();
};

onMounted(() => {
    loadData();
});
</script>

<template>

    <Head title="Usuarios" />

    <div class="p-6">

        <!-- HEADER -->

        <div class="mb-6 flex items-center justify-between">

            <div>

                <h1 class="text-xl font-semibold text-slate-800">
                    Gestión de Usuarios
                </h1>

                <p class="text-sm text-slate-400">
                    Administra los usuarios del sistema.
                </p>

            </div>

            <Button size="sm" class="gap-2" @click="openCreate">
                <Plus class="h-4 w-4" />

                Nuevo Usuario
            </Button>

        </div>

        <!-- TABLE -->

        <UserTable :users="users" @edit="openEdit" @delete="openDelete" />

        <!-- MODAL -->

        <UserModal :open="showModal" :user="selectedUser" :roles="roles" @close="showModal = false" @saved="onSaved" />

        <!-- DELETE -->

        <ConfirmDialog :open="showDeleteDialog" title="Eliminar usuario"
            :message="`¿Deseas eliminar al usuario '${userToDelete?.name}'?`" :loading="deleteLoading"
            variant="destructive" confirm-label="Eliminar" :confirmation-text="userToDelete?.name"
            @close="showDeleteDialog = false" @confirm="deleteUser" />

    </div>

</template>
