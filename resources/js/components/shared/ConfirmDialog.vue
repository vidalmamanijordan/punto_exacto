<script setup lang="ts">
import { AlertTriangle, Info, Trash2, X } from '@lucide/vue';
import { computed, ref, watch } from 'vue';
import {
    DialogClose,
    DialogContent,
    DialogOverlay,
    DialogPortal,
    DialogRoot,
} from 'reka-ui';
import { Button } from '@/components/ui/button';
import { DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

type Variant = 'destructive' | 'warning' | 'info';

const props = withDefaults(
    defineProps<{
        open: boolean;
        title: string;
        message: string;
        loading?: boolean;
        variant?: Variant;
        confirmLabel?: string;
        cancelLabel?: string;
        /** If provided, the user must type this exact text to enable the confirm button (double confirmation). */
        confirmationText?: string;
        /** Helper hint shown below the input when confirmationText is set. */
        confirmationHint?: string;
    }>(),
    {
        variant: 'destructive',
        confirmLabel: 'Confirmar',
        cancelLabel: 'Cancelar',
    },
);

const emit = defineEmits<{
    close: [];
    confirm: [];
}>();

const typedConfirmation = ref('');

const requiresDoubleConfirmation = computed(() => Boolean(props.confirmationText));

const isConfirmEnabled = computed(() => {
    if (!requiresDoubleConfirmation.value) {
        return true;
    }
    return typedConfirmation.value === props.confirmationText;
});

const variantConfig = computed(() => {
    const configs: Record<Variant, { icon: typeof Trash2; iconClass: string; badgeClass: string; confirmVariant: 'default' | 'destructive' | 'outline' | 'secondary' | 'ghost' | 'link' }> = {
        destructive: {
            icon: Trash2,
            iconClass: 'text-destructive',
            badgeClass: 'bg-destructive/10 dark:bg-destructive/20',
            confirmVariant: 'destructive',
        },
        warning: {
            icon: AlertTriangle,
            iconClass: 'text-amber-500',
            badgeClass: 'bg-amber-50 dark:bg-amber-900/20',
            confirmVariant: 'default',
        },
        info: {
            icon: Info,
            iconClass: 'text-blue-500',
            badgeClass: 'bg-blue-50 dark:bg-blue-900/20',
            confirmVariant: 'default',
        },
    };
    return configs[props.variant];
});

function handleClose(): void {
    if (props.loading) {
        return;
    }
    typedConfirmation.value = '';
    emit('close');
}

function handleConfirm(): void {
    if (!isConfirmEnabled.value || props.loading) {
        return;
    }
    emit('confirm');
}

watch(
    () => props.open,
    (isOpen) => {
        if (!isOpen) {
            typedConfirmation.value = '';
        }
    },
);
</script>

<template>
    <DialogRoot :open="open" @update:open="(val) => !val && handleClose()">
        <DialogPortal>
            <DialogOverlay
                class="data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 fixed inset-0 z-50 bg-black/30 backdrop-blur-sm duration-200" />
            <DialogContent
                class="bg-background data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 fixed top-[50%] left-[50%] z-50 grid w-full max-w-[calc(100%-2rem)] translate-x-[-50%] translate-y-[-50%] gap-4 rounded-lg border p-6 shadow-lg duration-200 sm:max-w-md">
                <DialogHeader>
                    <div class="flex items-start gap-4">
                        <div :class="[
                            'flex size-10 shrink-0 items-center justify-center rounded-full',
                            variantConfig.badgeClass,
                        ]">
                            <component :is="variantConfig.icon" :class="['size-5', variantConfig.iconClass]" />
                        </div>

                        <div class="space-y-1 pt-0.5">
                            <DialogTitle class="text-base leading-snug">
                                {{ title }}
                            </DialogTitle>
                            <DialogDescription class="text-sm">
                                {{ message }}
                            </DialogDescription>
                        </div>
                    </div>
                </DialogHeader>

                <!-- Double confirmation input -->
                <div v-if="requiresDoubleConfirmation" class="space-y-2">
                    <Label for="confirm-input" class="text-sm font-medium">
                        {{ confirmationHint ?? 'Confirmación requerida' }}
                    </Label>

                    <p class="text-muted-foreground text-xs">
                        Escribe
                        <span class="text-foreground font-semibold">{{ confirmationText }}</span>
                        para continuar.
                    </p>

                    <Input id="confirm-input" v-model="typedConfirmation" :placeholder="confirmationText"
                        :disabled="loading" :aria-invalid="typedConfirmation.length > 0 && !isConfirmEnabled"
                        autocomplete="off" @keydown.enter="handleConfirm" />

                    <p v-if="typedConfirmation.length > 0 && !isConfirmEnabled" class="text-destructive text-xs">
                        El texto no coincide.
                    </p>
                </div>

                <DialogFooter class="gap-2 sm:gap-2">
                    <Button class="bg-gray-800 text-white hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900"
                        :disabled="loading" @click="handleClose">
                        {{ cancelLabel }}
                    </Button>

                    <Button :variant="variantConfig.confirmVariant" :disabled="!isConfirmEnabled || loading"
                        @click="handleConfirm">
                        <component :is="variantConfig.icon" v-if="!loading" class="size-4" />
                        <span v-if="loading"
                            class="size-4 animate-spin rounded-full border-2 border-current border-t-transparent"
                            aria-hidden="true" />
                        {{ loading ? 'Procesando...' : confirmLabel }}
                    </Button>
                </DialogFooter>

                <DialogClose v-if="!loading"
                    class="ring-offset-background focus:ring-ring absolute top-4 right-4 rounded-xs opacity-70 transition-opacity hover:opacity-100 focus:ring-2 focus:ring-offset-2 focus:outline-hidden disabled:pointer-events-none">
                    <X class="size-4" />
                    <span class="sr-only">Cerrar</span>
                </DialogClose>
            </DialogContent>
        </DialogPortal>
    </DialogRoot>
</template>
