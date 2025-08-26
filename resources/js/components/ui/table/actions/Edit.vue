<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

import { Edit2 } from 'lucide-vue-next';

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <button
    data-slot="action-edit"
    :class="
      cn(
        'bg-blue-950/25 dark:bg-blue-600/25 hover:bg-blue-950/35 hover:dark:bg-blue-600/35 text-blue-950 dark:text-blue-600 size-6.5 rounded-md flex items-center justify-center cursor-pointer',
        props.class,
      )
    "
  >
    <Edit2 class="size-3.5" />
  </button>
</template>
