<script setup lang="ts">
import { ref, HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils'

import { Edit2Icon } from 'lucide-vue-next';

const props = defineProps<{
  name?: string;
  src?: Record<string, any>;
  imageClass? : HTMLAttributes['class']
  iconClass? : HTMLAttributes['class']
}>();

const filesUpdatedName = ref<string[]>([]);
const allFiles = ref<(File | null)[]>([]);

const emit = defineEmits<{
  (e: 'update:modelValue', value: (File | null)[] | null): void;
}>();

const handleFilesChange = (event: Event, key: number) => {
  const target = event.target as HTMLInputElement;
  const files = target.files;

  if (!files) return;

  filesUpdatedName.value[key] = files[0]?.name || '';

  allFiles.value[key] = files[0]; 

  emit('update:modelValue', allFiles.value);
}

const src = props.src;
</script>

<template>
    <div class="w-full flex items-center gap-2 overflow-x-auto custom-scrollbar">
      <div class="relative w-fit" v-for="(image, index) in src" :key="image.uuid || index">
        <img :src="image.original_url || '/images/default-image.avif'" alt=""
          :class="cn('rounded-full min-w-32 min-h-32 w-32 h-32 object-cover', props.imageClass)" />

        <div class="absolute top-0 right-3 z-10">
          <input type="file" :id="image.uuid" :name="props.name" class="hidden"
            @change="e => handleFilesChange(e, Number(index))" />

          <label :for="image.uuid" :class="cn('flex items-center justify-center w-7 h-7 rounded-full cursor-pointer text-for-bg-primary',
            'border-2 border-white dark:border-black bg-primary', props.iconClass)"
          >
            <Edit2Icon class="size-4" />
          </label>
        </div>

        <div v-if="filesUpdatedName[Number(index)]" class="absolute z-5 top-0 left-0 flex p-3 w-full h-full flex items-center justify-center
        bg-black/70 text-slate-100 rounded-full">
          <span class="text-xs font-bold">{{ filesUpdatedName[Number(index)] }}</span>
        </div>
      </div>
    </div>

</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 1px;
    height: 1px;
    scrollbar-width: thin;
    background: transparent !important;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent !important;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: transparent !important;
}
</style>