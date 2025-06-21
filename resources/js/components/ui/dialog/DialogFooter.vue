<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{ class?: HTMLAttributes['class'] }>()
</script>

<template>
  <div
    data-slot="dialog-footer"
    :class="cn('flex gap-2 items-center justify-end pt-2 border-t border-muted', props.class)"
  >
    <slot />
  </div>
</template>
