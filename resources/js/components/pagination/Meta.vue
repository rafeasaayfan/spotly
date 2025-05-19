<script setup lang="ts">
interface Meta {
    total: number;
    per_page: number;
    current_page: number;
    last_page: number;
    from: number;
    to: number;
}

const props = defineProps<{
    meta: Meta;
}>();
</script>

<template>
    <p class="flex items-center gap-2 text-xs text-gray-600/90 dark:text-gray-600/80">
        <span class="flex items-center gap-1">
            <span>Showing</span>
            <span>{{ props.meta.from }} to {{ props.meta.to }}</span>
            <span>of {{ props.meta.total }}</span>
        </span>

        <span>
            (Page {{ props.meta.current_page }} of {{ props.meta.last_page }})
        </span>
    </p>
</template>
