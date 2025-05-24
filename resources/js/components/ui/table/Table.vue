<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <table
    data-slot="table"
    :class="
      cn(
        'min-w-[900px] min-h-20 md:w-full bg-card rounded-md divide-y divide-slate-500/10 dark:divide-slate-600/10',
        props.class,
      )
    "
  >
    <slot />
  </table>
</template>
