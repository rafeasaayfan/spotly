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
  src?: string;
}>();

const fileUpdatedName = ref('');
const fileInputRef = ref<HTMLInputElement | null>(null);

const emit = defineEmits(['update:modelValue']);

function handleFileChange(event: Event) {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0] || null;
  fileUpdatedName.value = file?.name || '';
  emit('update:modelValue', file);
}

const src = props.src;
</script>

<template>
  <div>
    <!-- If preview image is provided -->
    <div v-if="props.src" class="relative w-fit">
      <img
        :src="src || '/images/default-image.avif'"
        alt=""
        class="rounded-full w-32 h-32 object-cover"
      />

      <!-- Pencil Icon File Input Trigger -->
      <div class="absolute top-0 right-3 z-10">
        <input
          type="file"
          :id="props.id || 'fileUpdate'"
          :name="props.name"
          class="hidden"
          ref="fileInputRef"
          @change="handleFileChange"
        />
        <label
          :for="props.id || 'fileUpdate'"
          class="flex items-center justify-center w-7 h-7 rounded-full cursor-pointer text-for-bg-primary
            border-2 border-white dark:border-black
            bg-primary"
        >
            <Edit2Icon class="size-4" />
        </label>
      </div>

      <!-- File Name Overlay -->
      <div
        v-show="fileUpdatedName"
        class="absolute z-5 top-0 left-0 flex p-3 w-full h-full flex items-center justify-center
         bg-black/70 text-slate-100 rounded-full"
      >
        <span class="text-xs">{{ fileUpdatedName }}</span>
      </div>
    </div>

    <!-- If no preview image (basic file input) -->
    <div v-else class="relative">
      <label
        class="relative flex items-center justify-center w-full text-sm cursor-pointer
          bg-gray-200 dark:bg-gray-900
          rounded-md border border-muted active:scale-105 transition-all focus:ring active:ring-blue-800 focus:ring-blue-800/50"
      >
        <input
          type="file"
          :id="props.id || props.name"
          :name="props.name"
          class="w-full text-sm focus:outline-none file:py-2 file:px-4 file:border-0 file:text-sm file:font-medium
            file:bg-slate-100 file:text-slate-950 hover:file:bg-slate-200
            dark:file:bg-slate-700 dark:file:text-slate-100 dark:hover:file:bg-slate-800 rounded-md
            file:cursor-pointer"
          :class="isRTL ? 'file:me-3 file:rounded-e-md' : 'file:mr-3 file:rounded-s-md'"
          @change="handleFileChange"
        />
      </label>
    </div>
  </div>
</template>
