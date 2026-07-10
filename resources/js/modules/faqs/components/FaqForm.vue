<script setup lang="ts">
import type { Category } from '@/modules/categories/types/category';
import AppCombobox from '@/components/shared/AppCombobox.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

defineProps<{
    form: {
        category_id: number | null;
        question: string;
        answer: string;
        is_active: boolean;
    };
    categories: Category[];
    errors: {
        category_id: string;
        question: string;
        answer: string;
    };
}>();

const selectClass = 'border-input h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm shadow-xs outline-none transition-[color,box-shadow] focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50';
</script>

<template>
    <div class="space-y-4">

        <!-- Categoría -->
        <div class="space-y-1.5">
            <Label>Categoría</Label>
            <AppCombobox
                v-model="form.category_id"
                :options="categories"
                placeholder="Buscar categoría..."
                empty-text="No se encontraron categorías"
            />
            <p v-if="errors.category_id" class="text-xs text-destructive">{{ errors.category_id }}</p>
        </div>

        <!-- Pregunta -->
        <div class="space-y-1.5">
            <Label for="faq-question">Pregunta</Label>
            <Input
                id="faq-question"
                v-model="form.question"
                placeholder="Ingrese la pregunta"
                :aria-invalid="!!errors.question"
            />
            <p v-if="errors.question" class="text-xs text-destructive">{{ errors.question }}</p>
        </div>

        <!-- Respuesta -->
        <div class="space-y-1.5">
            <Label for="faq-answer">Respuesta</Label>
            <textarea
                id="faq-answer"
                v-model="form.answer"
                rows="5"
                placeholder="Ingrese la respuesta"
                :class="[selectClass, 'h-auto resize-none py-2 leading-relaxed']"
            />
            <p v-if="errors.answer" class="text-xs text-destructive">{{ errors.answer }}</p>
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
                    FAQ activa
                </label>
                <p class="text-xs text-slate-400">La pregunta será visible en la plataforma</p>
            </div>
        </div>
    </div>
</template>
