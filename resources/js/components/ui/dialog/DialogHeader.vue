<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { X } from 'lucide-vue-next'
import {
  DialogClose,
} from 'reka-ui'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <div
    data-slot="dialog-header"
    :class="cn('w-full flex justify-between items-center gap-4 px-4 pb-2 border-b border-muted', props.class)"
  >
    <slot />

    <DialogClose
        class="bg-content size-7 flex items-center justify-center rounded-full cursor-pointer data-[state=open]:bg-accent data-[state=open]:text-muted-foreground opacity-80 transition-opacity hover:opacity-100 disabled:pointer-events-none [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4"
    >
        <X />
        <span class="sr-only">Close</span>
    </DialogClose>
  </div>
</template>
