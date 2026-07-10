<script setup lang="ts">
import { Check, ChevronsUpDown, X } from '@lucide/vue';
import { computed, nextTick, ref } from 'vue';

interface Option {
    id: number;
    name: string;
}

const props = withDefaults(
    defineProps<{
        modelValue: number | null;
        options: Option[];
        placeholder?: string;
        disabled?: boolean;
        emptyText?: string;
    }>(),
    {
        placeholder: 'Seleccionar...',
        disabled: false,
        emptyText: 'Sin resultados',
    },
);

const emit = defineEmits<{
    'update:modelValue': [number | null];
}>();

const query = ref('');
const buttonRef = ref<{ $el: HTMLButtonElement } | null>(null);

const selectedOption = computed(() =>
    props.options.find((o) => o.id === props.modelValue) ?? null,
);

const filteredOptions = computed(() => {
    if (!query.value.trim()) {
        return props.options;
    }
    return props.options.filter((o) =>
        o.name.toLowerCase().includes(query.value.toLowerCase()),
    );
});

function handleFocus(isOpen: boolean): void {
    query.value = '';
    if (!isOpen) {
        nextTick(() => buttonRef.value?.$el.click());
    }
}

function handleSelect(option: Option | null): void {
    emit('update:modelValue', option?.id ?? null);
    query.value = '';
}

function clearSelection(): void {
    emit('update:modelValue', null);
    query.value = '';
}
</script>

<template>
    <HCombobox
        v-slot="{ open }"
        :model-value="selectedOption"
        :disabled="disabled"
        nullable
        by="id"
        @update:model-value="handleSelect"
    >
        <div class="relative">
            <!-- Input + buttons wrapper -->
            <div
                class="border-input focus-within:border-ring focus-within:ring-ring/50 relative flex h-9 w-full items-center rounded-md border bg-transparent shadow-xs transition-[color,box-shadow] focus-within:ring-[3px]"
            >
                <HComboboxInput
                    class="h-full w-full bg-transparent px-3 py-1 text-sm outline-none placeholder:text-muted-foreground disabled:cursor-not-allowed disabled:opacity-50"
                    :placeholder="selectedOption ? selectedOption.name : placeholder"
                    :display-value="(opt: any) => opt?.name ?? ''"
                    autocomplete="off"
                    @focus="handleFocus(open)"
                    @change="query = $event.target.value"
                    @blur="query = ''"
                />

                <!-- Clear button -->
                <button
                    v-if="selectedOption && !disabled"
                    type="button"
                    class="mr-1 flex h-5 w-5 shrink-0 items-center justify-center rounded text-muted-foreground transition-colors hover:text-foreground"
                    tabindex="-1"
                    @click.stop="clearSelection"
                >
                    <X class="h-3 w-3" />
                </button>

                <!-- Chevron button -->
                <HComboboxButton
                    ref="buttonRef"
                    class="mr-2 flex h-5 w-5 shrink-0 items-center justify-center rounded text-muted-foreground transition-colors hover:text-foreground"
                >
                    <ChevronsUpDown class="h-3.5 w-3.5" />
                </HComboboxButton>
            </div>

            <!-- Dropdown -->
            <HComboboxOptions
                class="absolute z-50 mt-1 max-h-56 w-full overflow-auto rounded-md border border-slate-200 bg-white py-1 shadow-lg outline-none"
            >
                <!-- Empty state -->
                <li
                    v-if="filteredOptions.length === 0"
                    class="px-3 py-2 text-sm text-muted-foreground"
                >
                    {{ emptyText }}
                </li>

                <HComboboxOption
                    v-for="option in filteredOptions"
                    :key="option.id"
                    v-slot="{ active, selected }"
                    :value="option"
                    as="template"
                >
                    <li
                        :class="[
                            'relative flex cursor-pointer select-none items-center gap-2 px-3 py-2 text-sm transition-colors',
                            active ? 'bg-slate-50 text-slate-900' : 'text-slate-700',
                        ]"
                    >
                        <span
                            :class="[
                                'flex h-4 w-4 shrink-0 items-center justify-center rounded',
                                selected ? 'text-slate-900' : 'text-transparent',
                            ]"
                        >
                            <Check class="h-3.5 w-3.5" />
                        </span>
                        <span :class="['truncate', selected && 'font-medium']">
                            {{ option.name }}
                        </span>
                    </li>
                </HComboboxOption>
            </HComboboxOptions>
        </div>
    </HCombobox>
</template>
