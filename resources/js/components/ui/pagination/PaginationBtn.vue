<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps<{
  active?: boolean,
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <button
    data-slot="pagination-btn"
    :class="
      cn(
        'bg-gray-600/25 dark:bg-gray-700/25 hover:bg-gray-600/15 hover:dark:bg-gray-700/50',
        'text-gray-950/50 dark:text-gray-100/50 text-sm',
        'w-8 h-8 rounded-md flex items-center justify-center cursor-pointer',
        {'bg-gray-600/10 dark:bg-gray-700/60 text-blue-700 dark:text-blue-600 font-semibold': active},
        props.class,
      )
    "
  >
    <slot />
  </button>
</template>
