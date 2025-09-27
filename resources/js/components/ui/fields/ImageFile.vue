<script setup lang="ts">
import { ref, computed, HTMLAttributes, watch } from 'vue';
import { cn } from '@/lib/utils'

import { Edit2Icon } from 'lucide-vue-next';

const props = defineProps<{
  id?: string;
  name?: string;
  src?: string;
  imageClass?: HTMLAttributes['class']
  iconClass?: HTMLAttributes['class']
}>();

const fileUpdatedName = ref('');

const emit = defineEmits<{
  (e: 'update:modelValue', value: File | null): void;
}>();

const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const files = target.files;

  if (!files) return;

  fileUpdatedName.value = files[0]?.name || '';

  emit('update:modelValue', files[0]);
}

const src = props.src;
</script>

<template>
  <div class="relative w-fit">
    <img :src="src ?? '/images/default-image.avif'" alt="" :class="cn('rounded-full w-32 h-32 object-cover', props.imageClass)" />

    <!-- Pencil Icon File Input Trigger -->
    <div class="absolute top-0 right-3 z-10">
      <input type="file" :id="props.id || 'fileUpdate'" :name="props.name" class="hidden" @change="handleFileChange" />
      <label :for="props.id || 'fileUpdate'" :class="cn('flex items-center justify-center w-7 h-7 rounded-full cursor-pointer', 
            'text-for-bg-primary border-2 border-white dark:border-black bg-primary', props.iconClass)">
        <Edit2Icon class="size-4" />
      </label>
    </div>

    <!-- File Name Overlay -->
    <div v-if="fileUpdatedName" class="absolute z-5 top-0 left-0 flex p-3 w-full h-full flex items-center justify-center
         bg-black/70 text-slate-100 rounded-full">
      <span class="text-xs font-bold">{{ fileUpdatedName }}</span>
    </div>
  </div>
</template>