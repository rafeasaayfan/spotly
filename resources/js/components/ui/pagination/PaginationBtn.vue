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
        'bg-content',
        'text-body-muted text-sm',
        'w-8 h-8 rounded-md flex items-center justify-center cursor-pointer',
        {'bg-content-active text-active font-semibold': active},
        props.class,
      )
    "
  >
    <slot />
  </button>
</template>
