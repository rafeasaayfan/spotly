<script setup lang="ts">
import { computed, HTMLAttributes } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { SharedData } from '@/types';
import { cn } from '@/lib/utils'

const page = usePage<SharedData>();
const isRTL = computed(() => page.props.lang === 'ar');

const props = defineProps<{
  id?: string;
  name?: string;
  multiple?: boolean;
  fileClass? : HTMLAttributes['class']
  fileInputClass? : HTMLAttributes['class']
}>();

const emit = defineEmits<{
  (e: 'update:modelValue', value: File | File[] | null | (File | null)[]): void;
}>();

const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const files = target.files;

  if (!files) return;

  emit('update:modelValue', props.multiple ? Array.from(files) : files[0]);
}
</script>

<template>
    <div class="relative">
      <label
        :class="cn('relative flex items-center justify-center w-full text-sm cursor-pointer',
          'bg-field rounded-md border border-muted active:scale-98 transition-all duration-150 ease-in-out', 
          'focus:ring active:ring-blue-800 focus:ring-blue-800/50', props.fileClass)">
        <input :multiple="props.multiple || false" type="file" :id="props.id || props.name"
          :name="props.multiple ? `${props.name}[]` : props.name" :class="cn('w-full text-sm focus:outline-none file:py-2 file:px-4 file:border-0 file:text-sm file:font-medium',
            'file:bg-zinc-100 file:text-black/80 hover:file:bg-zinc-200',
            'dark:file:bg-zinc-900 dark:file:text-white/80 dark:hover:file:bg-zinc-800 rounded-md',
            'file:cursor-pointer file:rounded-s-md', isRTL ? 'file:me-3' : 'file:mr-3', props.fileInputClass)"
          @change="handleFileChange" />
      </label>
    </div>
</template>