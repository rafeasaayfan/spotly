<script setup lang="ts">
import { cn } from '@/lib/utils'
import {
  DropdownMenuContent,
  type DropdownMenuContentEmits,
  type DropdownMenuContentProps,
  DropdownMenuPortal,
  useForwardPropsEmits,
} from 'reka-ui'
import { computed, type HTMLAttributes } from 'vue'
import type { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

const props = withDefaults(
  defineProps<DropdownMenuContentProps & { class?: HTMLAttributes['class'], align?: string }>(),
  {
    sideOffset: 4,
  },
)
const emits = defineEmits<DropdownMenuContentEmits>()

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props

  return delegated
})

const forwarded = useForwardPropsEmits(delegatedProps, emits)

const page = usePage<SharedData>();

const lang = computed(() => page.props.lang)

const align = computed(() => {
  if (!props.align) {
    return lang.value == 'ar' ? 'start' : 'end'
  }

  return props.align;
})
</script>

<template>
  <DropdownMenuPortal>
    <DropdownMenuContent data-slot="dropdown-menu-content" v-bind="forwarded" :align="align" :class="cn(
      'bg-body z-50 overflow-x-hidden overflow-y-auto rounded-md border border-muted p-2 shadow-md dark:shadow-[0_4px_6px_-1px_rgb(255_255_255_/_0.03),_0_2px_4px_-2px_rgb(255_255_255_/_0.03)]',
      'data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95',
      'data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95',
      'data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2',
      'max-h-(--reka-dropdown-menu-content-available-height) ',
      'origin-(--reka-dropdown-menu-content-transform-origin)',
      props.class
    )">
      <slot />
    </DropdownMenuContent>

  </DropdownMenuPortal>
</template>
