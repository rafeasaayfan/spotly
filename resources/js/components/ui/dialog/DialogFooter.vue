<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{ class?: HTMLAttributes['class'] }>()
</script>

<template>
  <div
    data-slot="dialog-footer"
    :class="cn('flex gap-2 items-center justify-end py-3 px-4 bg-body rounded-b-lg border-t border-muted', props.class)"
  >
    <slot />
  </div>
</template>
