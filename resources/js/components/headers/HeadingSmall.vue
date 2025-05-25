<script setup lang="ts">
interface Props {
    title: string;
    description?: string;
}

defineProps<Props>();
</script>

<template>
    <header class="w-fit">
        <h3 class="mb-0.5 text-active font-medium">{{ title }}</h3>
        <p v-if="description" class="text-sm text-body-muted">
            {{ description }}
        </p>
    </header>
</template>
