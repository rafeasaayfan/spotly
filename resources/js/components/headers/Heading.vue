<script setup lang="ts">

interface Props {
    title: string;
    description?: string;
}

defineProps<Props>();
</script>

<template>
    <div class="space-y-0.5 w-fit">
        <h2 class="text-xl font-semibold tracking-tight text-active">{{ title }}</h2>
        <p v-if="description" class="text-sm text-body-muted">
            {{ description }}
        </p>
    </div>
</template>
