<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

import { Eye } from 'lucide-vue-next';

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <button
    data-slot="action-view"
    :class="
      cn(
        'bg-green-700/25 dark:bg-green-600/25 hover:bg-green-700/35 hover:dark:bg-green-600/35 text-green-700 dark:text-green-600 w-6.5 h-6.5 rounded-md flex items-center justify-center cursor-pointer',
        props.class,
      )
    "
  >
    <Eye class="size-3.5" />
  </button>
</template>
