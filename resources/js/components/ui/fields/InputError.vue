<script setup lang="ts">
defineProps<{
    message?: string;
}>();
</script>

<template>
    <div v-show="message">
        <p class="text-sm text-active-link-2">
            {{ message }}
        </p>
    </div>
</template>
