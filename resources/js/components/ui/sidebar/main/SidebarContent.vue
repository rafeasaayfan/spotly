<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <div
    data-slot="sidebar-content"
    data-sidebar="content"
    :class="cn('flex-1 overflow-auto group-data-[collapsible=icon]:overflow-hidden', props.class)"
  >
    <slot />
  </div>
</template>
