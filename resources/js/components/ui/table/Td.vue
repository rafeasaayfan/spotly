<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <td
    data-slot="td"
    :class="
      cn(
        'py-3 border-muted border-s text-start px-3',
        props.class,
      )
    "
  >
    <slot />
  </td>
</template>
