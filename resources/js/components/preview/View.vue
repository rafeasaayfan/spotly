<script setup lang="ts">
import { DialogDescription, DialogHeader, DialogScrollContent, DialogTitle } from '@/components/ui/dialog';
import { defineAsyncComponent, DefineComponent, watch, computed, ref } from 'vue';

const props = defineProps<{
    data: {
        colors: Record<string, string>;
        path: string;
    }
}>();

defineEmits<{
    (e: 'close'): void;
}>();

const components = import.meta.glob('@/pages/preview/**/**/pages/home/Home.vue');
const refPath = ref(props.data.path);
watch(() => props.data.path, (newPath) => {
  refPath.value = newPath;
}, { immediate: true });
const matchingPath = computed(() => {
  return Object.keys(components).find(path => path.includes(refPath.value));
});
const actionCompo = computed(() => {
  const path = matchingPath.value;
  if (!path) throw new Error(`Component not found for path: ${refPath.value}`);
  return defineAsyncComponent(components[path] as () => Promise<DefineComponent>);
});
</script>

<template>
    <DialogScrollContent class="sm:max-w-[calc(100%-3rem)] md:max-w-[calc(100%-3rem)] lg:max-w-[calc(100%-5rem)]">
        <DialogHeader>
            <DialogTitle>Preview</DialogTitle>
            <DialogDescription class="sr-only"> No description provided. </DialogDescription>
        </DialogHeader>

        <component :is="actionCompo" :colors="props.data.colors" />
    </DialogScrollContent>
</template>
