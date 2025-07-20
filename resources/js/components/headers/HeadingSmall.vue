<script setup lang="ts">
import { cn } from '@/lib/utils';

interface Props {
    title: string;
    description?: string;
    class?: string;
    titleClass?: string;
    descriptionClass?: string;
}

const props = defineProps<Props>();
</script>

<template>
    <header :class="cn('w-fit ps-2 border-s border-muted', props.class)">
        <h3 :class="cn('text-active font-medium', props.titleClass)">{{ title }}</h3>
        <p v-if="description" :class="cn('text-sm text-body-muted opacity-70', props.descriptionClass)">
            {{ description }}
        </p>
    </header>
</template>
