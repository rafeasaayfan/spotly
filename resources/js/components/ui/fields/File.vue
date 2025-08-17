<script setup lang="ts">
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { SharedData } from '@/types';

import { Edit2Icon } from 'lucide-vue-next';

const page = usePage<SharedData>();
const isRTL = computed(() => page.props.lang === 'ar');

const props = defineProps<{
  id?: string;
  name?: string;
  label?: string;
  src?: string | Record<string, any>;
  multiple?: boolean;
}>();

const filesUpdatedName = ref<string[]>([]);
const allFiles = ref<(File | null)[]>([]);

const fileUpdatedName = ref('');

const emit = defineEmits<{
  (e: 'update:modelValue', value: File | File[] | null | (File | null)[]): void;
}>();

const handleFilesChange = (event: Event, key: number) => {
  const target = event.target as HTMLInputElement;
  const files = target.files;

  if (!files) return;

  filesUpdatedName.value[key] = files.length > 1
    ? `${files.length} files selected`
    : files[0]?.name || '';

  allFiles.value[key] = files[0]; 

  emit('update:modelValue', allFiles.value);
}

const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const files = target.files;

  if (!files) return;

  fileUpdatedName.value = files[0]?.name || '';

  emit('update:modelValue', props.multiple ? Array.from(files) : files[0]);
}

const src = props.src;
</script>

<template>
  <div>
    <!-- If no preview image (basic file input) -->
    <div v-if="!src" class="relative">
      <label
        class="relative flex items-center justify-center w-full text-sm cursor-pointer
          bg-field
          rounded-md border border-muted active:scale-98 transition-all duration-150 ease-in-out focus:ring active:ring-blue-800 focus:ring-blue-800/50">
        <input :multiple="props.multiple || false" type="file" :id="props.id || props.name"
          :name="props.multiple ? `${props.name}[]` : props.name" class="w-full text-sm focus:outline-none file:py-2 file:px-4 file:border-0 file:text-sm file:font-medium
            file:bg-zinc-100 file:text-black/80 hover:file:bg-zinc-200
            dark:file:bg-zinc-900 dark:file:text-white/80 dark:hover:file:bg-zinc-800 rounded-md
            file:cursor-pointer" :class="isRTL ? 'file:me-3 file:rounded-e-md' : 'file:mr-3 file:rounded-s-md'"
          @change="handleFileChange" />
      </label>
    </div>

    <!-- If preview image is provided -->
    <div v-else-if="typeof src === 'object'"
      class="w-fit flex items-center gap-2 overflow-x-auto">
      <div class="relative w-fit" v-for="(image, index) in Object.values(src)" :key="image.uuid || index">
        <img :src="image.original_url || '/images/default-image.avif'" alt=""
          class="rounded-full w-32 h-32 object-cover" />

        <div class="absolute top-0 right-3 z-10">
          <input type="file" :id="image.uuid" :name="props.name" class="hidden"
            @change="e => handleFilesChange(e, index)" />
          <label :for="image.uuid" class="flex items-center justify-center w-7 h-7 rounded-full cursor-pointer text-for-bg-primary
            border-2 border-white dark:border-black bg-primary"
          >
            <Edit2Icon class="size-4" />
          </label>
        </div>

        <div v-if="filesUpdatedName[index]" class="absolute z-5 top-0 left-0 flex p-3 w-full h-full flex items-center justify-center
        bg-black/70 text-slate-100 rounded-full">
          <span class="text-xs">{{ filesUpdatedName[index] }}</span>
        </div>
      </div>
    </div>

    <div v-else-if="typeof src === 'string'"
      class="relative w-fit">
      <img :src="src || '/images/default-image.avif'" alt="" class="rounded-full w-32 h-32 object-cover" />

      <!-- Pencil Icon File Input Trigger -->
      <div class="absolute top-0 right-3 z-10">
        <input type="file" :id="props.id || 'fileUpdate'" :name="props.name" class="hidden"
          @change="handleFileChange" />
        <label :for="props.id || 'fileUpdate'" class="flex items-center justify-center w-7 h-7 rounded-full cursor-pointer text-for-bg-primary
            border-2 border-white dark:border-black
            bg-primary">
          <Edit2Icon class="size-4" />
        </label>
      </div>

      <!-- File Name Overlay -->
      <div v-if="fileUpdatedName" class="absolute z-5 top-0 left-0 flex p-3 w-full h-full flex items-center justify-center
         bg-black/70 text-slate-100 rounded-full">
        <span class="text-xs">{{ fileUpdatedName }}</span>
      </div>
    </div>

  </div>
</template>
