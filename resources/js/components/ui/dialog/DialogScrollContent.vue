<script setup lang="ts">
import { cn } from '@/lib/utils'
import {
  DialogContent,
  type DialogContentEmits,
  type DialogContentProps,
  DialogOverlay,
  DialogPortal,
  useForwardPropsEmits,
} from 'reka-ui'
import { computed, type HTMLAttributes } from 'vue'
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

const props = defineProps<DialogContentProps & { class?: HTMLAttributes['class'] }>()
const emits = defineEmits<DialogContentEmits>()

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props

  return delegated
})

const forwarded = useForwardPropsEmits(delegatedProps, emits)

const page = usePage<SharedData>();
</script>

<template>
  <DialogPortal>
    <DialogOverlay
      class="fixed inset-0 z-50 grid place-items-center overflow-y-auto bg-black/80 data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0"
    >
      <DialogContent
        :dir="page.props.lang === 'ar' ? 'rtl' : 'ltr'"
        :class="
          cn(
            'relative z-50 grid w-full my-10 border border-muted bg-popover shadow-lg rounded-lg transition-all duration-100 ease-in-out',
            'max-w-[calc(100%-1rem)] sm:max-w-lg md:max-w-xl lg:max-w-3xl',
            props.class,
          )
        "
        v-bind="forwarded"
        @pointer-down-outside="(event) => {
          const originalEvent = event.detail.originalEvent;
          const target = originalEvent.target as HTMLElement;
          if (originalEvent.offsetX > target.clientWidth || originalEvent.offsetY > target.clientHeight) {
            event.preventDefault();
          }
        }"
      >
        <slot />

      </DialogContent>
    </DialogOverlay>
  </DialogPortal>
</template>
