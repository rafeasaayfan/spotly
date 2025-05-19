<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

import { View } from 'lucide-vue-next';

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <button
    data-slot="action-view"
    :class="
      cn(
        'bg-green-700/25 dark:bg-green-600/25 hover:bg-green-700/35 hover:dark:bg-green-600/35 text-green-700 dark:text-green-600 w-7 h-7 rounded-md flex items-center justify-center cursor-pointer',
        props.class,
      )
    "
  >
    <View class="w-4 h-4" />
  </button>
</template>
