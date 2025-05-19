<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <tbody
    data-slot="tbody"
    :class="
      cn(
        'divide-y divide-slate-500/10 dark:divide-slate-600/10',
        props.class,
      )
    "
  >
    <slot />
  </tbody>
</template>
