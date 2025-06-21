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
        'min-w-[900px] min-h-20 md:w-full rounded-md border-y border-muted',
        props.class,
      )
    "
  >
    <slot />
  </table>
</template>
