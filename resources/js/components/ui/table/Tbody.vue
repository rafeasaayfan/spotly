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
        props.class,
      )
    "
  >
    <slot />
  </tbody>
</template>
