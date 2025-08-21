<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <span
    data-slot="dropdown-menu-shortcut"
    :class="cn('text-body-muted font-semibold ml-auto text-xs tracking-widest px-1', props.class)"
  >
    <slot />
  </span>
</template>
