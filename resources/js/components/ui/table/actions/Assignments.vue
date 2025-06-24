<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

import { PercentDiamondIcon } from 'lucide-vue-next';

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <button
    data-slot="action-assignments"
    :class="
      cn(
        'bg-yellow-600/25 dark:bg-yellow-500/25 hover:bg-yellow-600/35 hover:dark:bg-yellow-500/35 text-yellow-600 dark:text-yellow-500 w-6.5 h-6.5 rounded-md flex items-center justify-center cursor-pointer',
        props.class,
      )
    "
  >
    <PercentDiamondIcon class="size-3.5" />
  </button>
</template>
