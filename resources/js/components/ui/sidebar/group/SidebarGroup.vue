<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <div
    data-slot="sidebar-group"
    data-sidebar="group"
    :class="cn('relative flex w-full min-w-0 flex-col px-4 py-2 group-data-[collapsible=icon]:px-2 group-data-[collapsible=icon]:py-4', props.class)"
  >
    <slot />
  </div>
</template>
