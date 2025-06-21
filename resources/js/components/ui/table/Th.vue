<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <th
    data-slot="th"
    :class="
      cn(
        'py-2 min-w-10 border-muted text-start border-s px-3 bg-nav',
        props.class,
      )
    "
  >
    <slot />
  </th>
</template>
