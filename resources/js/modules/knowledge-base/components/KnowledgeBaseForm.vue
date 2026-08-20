<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

defineProps<{
    form: {
        title: string;
        content: string;
        is_active: boolean;
    };
    errors: {
        title: string;
        content: string;
    };
}>();

const textareaClass = 'border-input h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm shadow-xs outline-none transition-[color,box-shadow] focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50';
</script>

<template>
    <div class="space-y-4">

        <!-- Título -->
        <div class="space-y-1.5">
            <Label for="kb-title">Título</Label>
            <Input
                id="kb-title"
                v-model="form.title"
                placeholder="Ingrese el título"
                :aria-invalid="!!errors.title"
            />
            <p v-if="errors.title" class="text-xs text-destructive">{{ errors.title }}</p>
        </div>

        <!-- Contenido -->
        <div class="space-y-1.5">
            <Label for="kb-content">Contenido</Label>
            <textarea
                id="kb-content"
                v-model="form.content"
                rows="6"
                placeholder="Ingrese el contenido"
                :class="[textareaClass, 'h-auto resize-none py-2 leading-relaxed']"
            />
            <p v-if="errors.content" class="text-xs text-destructive">{{ errors.content }}</p>
        </div>

        <!-- Estado -->
        <div class="flex items-center gap-3 rounded-lg border border-slate-100 bg-slate-50/60 px-4 py-3">
            <input
                id="is_active"
                v-model="form.is_active"
                type="checkbox"
                class="h-4 w-4 rounded border-slate-300 accent-slate-900"
            />
            <div>
                <label for="is_active" class="cursor-pointer text-sm font-medium text-slate-700">
                    Artículo activo
                </label>
                <p class="text-xs text-slate-400">El artículo será visible en la plataforma</p>
            </div>
        </div>
    </div>
</template>
