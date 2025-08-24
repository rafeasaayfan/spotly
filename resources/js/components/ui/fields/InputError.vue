<script setup lang="ts">
import { cn } from '@/lib/utils';

const props = defineProps<{
    class?: string;
    message?: string;
}>();
</script>

<template>
    <div v-show="message">
        <p :class="cn('text-sm text-active-link-2', props.class)">
            {{ message }}
        </p>
    </div>
</template>
