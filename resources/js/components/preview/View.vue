<script setup lang="ts">
import { DialogDescription, DialogHeader, DialogScrollContent, DialogTitle } from '@/components/ui/dialog';
import Home from '@/pages/preview/e-commerce/spotly-ui/pages/home/Home.vue';

const props = defineProps<{
    data: {
        colors: Record<string, string>;
        path: string;
    }
}>();
</script>

<template>
    <DialogScrollContent class="sm:max-w-[calc(100%-3rem)] md:max-w-[calc(100%-3rem)] lg:max-w-[calc(100%-5rem)]">
        <DialogHeader>
            <DialogTitle>Preview</DialogTitle>
            <DialogDescription class="sr-only"> No description provided. </DialogDescription>
        </DialogHeader>

        <Home :colors="props.data.colors" />
    </DialogScrollContent>
</template>
