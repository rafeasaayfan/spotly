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
        'text-xs border-b border-muted',
        props.class,
      )
    "
  >
    <slot />
  </tr>
</template>
