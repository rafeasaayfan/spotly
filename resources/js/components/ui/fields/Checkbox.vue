<script setup lang="ts">
import type { CheckboxRootEmits, CheckboxRootProps } from 'reka-ui'
import { cn } from '@/lib/utils'
import { Check } from 'lucide-vue-next'
import { CheckboxIndicator, CheckboxRoot, useForwardPropsEmits } from 'reka-ui'
import { computed, type HTMLAttributes } from 'vue'

const props = defineProps<CheckboxRootProps & { class?: HTMLAttributes['class'] }>()
const emits = defineEmits<CheckboxRootEmits>()

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props
  return delegated
})

const forwarded = useForwardPropsEmits(delegatedProps, emits)
</script>

<template>
  <CheckboxRoot
    data-slot="checkbox"
    v-bind="forwarded"
    :class="
      cn(
        'cursor-pointer rounded border-0 w-[19px] h-[19px] flex justify-center items-center transition-transform duration-300 active:scale-95 focus:outline-none focus:ring-0',
        'bg-slate-300 dark:bg-slate-950',
        'data-[state=checked]:bg-blue-800 data-[state=checked]:dark:bg-blue-600',
        props.class
      )
    "
  >
    <CheckboxIndicator
      data-slot="checkbox-indicator"
      class="flex items-center justify-center text-current transition-none data-[state=checked]:text-white"
    >
      <slot>
        <Check class="size-3.5" />
      </slot>
    </CheckboxIndicator>
  </CheckboxRoot>
</template>
