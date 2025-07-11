<script setup lang="ts">
interface Props {
    title: string;
    description?: string;
}

defineProps<Props>();
</script>

<template>
    <header class="w-fit ps-2 border-s border-muted">
        <h3 class="text-active font-medium">{{ title }}</h3>
        <p v-if="description" class="text-sm text-body-muted opacity-70">
            {{ description }}
        </p>
    </header>
</template>
