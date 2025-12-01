<script setup lang="ts">
const props = defineProps<{
    gridSize?: number;
}>();
</script>

<template>
    <div class="rounded-md bg-[var(--content-3-bg-hover)] p-2 border border-muted">
        <div :class="['border-muted bg-body grid rounded-md border grid-cols-1 sm:grid-cols-2 lg:grid-cols-' + (props.gridSize ?? 4)]">
            <slot />
        </div>
    </div>
</template>
