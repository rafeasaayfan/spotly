<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

import { Trash2 } from 'lucide-vue-next';

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <button
    data-slot="action-delete"
    :class="
      cn(
        'bg-red-600/25 dark:bg-red-500/25 hover:bg-red-600/35 hover:dark:bg-red-500/35 text-red-600 dark:text-red-500 w-7 h-7 rounded-md flex items-center justify-center cursor-pointer',
        props.class,
      )
    "
  >
    <Trash2 class="w-4 h-4" />
  </button>
</template>
