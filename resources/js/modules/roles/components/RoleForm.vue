<script setup lang="ts">
import { computed } from 'vue';
import {
    AccordionRoot,
    AccordionItem,
    AccordionHeader,
    AccordionTrigger,
    AccordionContent,
} from 'reka-ui';
import { ChevronRight, ChevronDown } from '@lucide/vue';
import type { Permission } from '../types/permission';

const props = defineProps<{
    form: {
        name: string;
        permissions: string[];
    };
    permissions: Permission[];
    errors: Record<string, string>;
}>();

function getModule(name: string) {
    return name.split('.')[0];
}

function formatPermission(name: string) {
    return name
        .split('.')
        .slice(1)
        .join('.');
}

const groupedPermissions = computed(() => {
    return props.permissions.reduce(
        (acc, permission) => {
            const module = getModule(
                permission.name
            );

            if (!acc[module]) {
                acc[module] = [];
            }

            acc[module].push(permission);

            return acc;
        },
        {} as Record<string, Permission[]>
    );
});

function isChecked(name: string) {
    return props.form.permissions.includes(
        name
    );
}

function togglePermission(name: string) {
    const index =
        props.form.permissions.indexOf(name);

    if (index === -1) {
        props.form.permissions.push(name);
    } else {
        props.form.permissions.splice(
            index,
            1
        );
    }
}

function isModuleChecked(module: string) {
    const perms =
        groupedPermissions.value[
            module
        ].map((p) => p.name);

    return perms.every((p) =>
        props.form.permissions.includes(p)
    );
}

function toggleModule(module: string) {
    const perms =
        groupedPermissions.value[
            module
        ].map((p) => p.name);

    const allSelected = perms.every((p) =>
        props.form.permissions.includes(p)
    );

    if (allSelected) {
        props.form.permissions =
            props.form.permissions.filter(
                (p) => !perms.includes(p)
            );
    } else {
        perms.forEach((p) => {
            if (
                !props.form.permissions.includes(
                    p
                )
            ) {
                props.form.permissions.push(p);
            }
        });
    }
}
</script>

<template>
    <div class="space-y-6">
        <!-- PERMISOS -->
        <div>
            <label class="text-sm text-slate-600">
                Permisos
            </label>

            <p v-if="errors.permissions" class="mb-2 text-xs text-red-500">
                {{ errors.permissions }}
            </p>

            <AccordionRoot type="multiple" class="space-y-3">
                <AccordionItem v-for="(
perms,
    module
                    ) in groupedPermissions" :key="module" :value="module"
                    class="rounded-xl border border-slate-200 bg-slate-50/40 transition-colors data-[state=open]:bg-white data-[state=open]:shadow-sm">
                    <!-- HEADER -->
                    <AccordionHeader class="flex items-center justify-between px-4 py-3">
                        <AccordionTrigger class="group flex w-full items-center justify-between">
                            <div class="flex items-center gap-2">
                                <!-- ICONO CLOSED -->
                                <span class="group-data-[state=open]:hidden">
                                    <ChevronRight class="h-4 w-4 text-slate-400" />
                                </span>

                                <!-- ICONO OPEN -->
                                <span class="hidden group-data-[state=open]:inline-flex">
                                    <ChevronDown class="h-4 w-4 text-slate-700" />
                                </span>

                                <!-- MODULE -->
                                <span
                                    class="text-sm font-semibold text-slate-700 capitalize transition-colors data-[state=open]:text-slate-900">
                                    {{
                                        module
                                    }}
                                </span>
                            </div>

                            <span class="text-xs text-slate-400">
                                {{
                                    isModuleChecked(
                                        module
                                    )
                                        ? 'Todo seleccionado'
                                        : ''
                                }}
                            </span>
                        </AccordionTrigger>

                        <!-- SELECT ALL -->
                        <button type="button" class="ml-4 text-xs text-slate-500 hover:text-slate-700" @click.stop="
                            toggleModule(
                                module
                            )
                            ">
                            {{
                                isModuleChecked(
                                    module
                                )
                                    ? 'Quitar todo'
                                    : 'Seleccionar todo'
                            }}
                        </button>
                    </AccordionHeader>

                    <!-- CONTENT -->
                    <AccordionContent class="px-4 pb-4">
                        <div class="space-y-2">
                            <label v-for="perm in perms" :key="perm.id
                                " class="flex cursor-pointer items-center gap-2 text-sm text-slate-600">
                                <input type="checkbox"
                                    class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400"
                                    :checked="isChecked(
                                        perm.name
                                    )
                                        " @change="
                                            togglePermission(
                                                perm.name
                                            )
                                            " />

                                <span>
                                    {{
                                        formatPermission(
                                            perm.name
                                        )
                                    }}
                                </span>
                            </label>
                        </div>
                    </AccordionContent>
                </AccordionItem>
            </AccordionRoot>
        </div>
    </div>
</template>