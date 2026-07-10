<script setup lang="ts">
import { Toaster } from 'vue-sonner';
import 'vue-sonner/style.css';
</script>

<template>
    <Toaster position="top-right" rich-colors close-button expand :visible-toasts="5" />
</template>