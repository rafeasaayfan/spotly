<script setup lang="ts">
import { cn } from '@/lib/utils'
import {
  DialogContent,
  type DialogContentEmits,
  type DialogContentProps,
  DialogPortal,
  useForwardPropsEmits,
} from 'reka-ui'
import { computed, type HTMLAttributes } from 'vue'
import DialogOverlay from './DialogOverlay.vue'

const props = defineProps<DialogContentProps & { class?: HTMLAttributes['class'] }>()
const emits = defineEmits<DialogContentEmits>()

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props

  return delegated
})

const forwarded = useForwardPropsEmits(delegatedProps, emits)
</script>

<template>
  <DialogPortal>
    <DialogOverlay />
    <DialogContent
      data-slot="dialog-content"
      v-bind="forwarded"
      :class="
        cn(
          'bg-popover z-50 grid rounded-lg border border-muted shadow-lg transition-all duration-100 ease-in-out',
          'data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95',
          'w-full max-w-[calc(100%-1rem)] sm:max-w-lg md:max-w-xl lg:max-w-2xl min-h-20',
          'fixed top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%]',
          props.class,
        )"
    >
      <slot />
    </DialogContent>
  </DialogPortal>
</template>
