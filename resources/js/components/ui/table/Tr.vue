<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <tr
    data-slot="tr"
    :class="
      cn(
        'text-xs text-slate-950/50 dark:text-slate-100/50',
        props.class,
      )
    "
  >
    <slot />
  </tr>
</template>
