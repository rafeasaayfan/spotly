<script setup lang="ts">
import { cn } from '@/lib/utils'
import { useVModel } from '@vueuse/core'
import type { HTMLAttributes } from 'vue'

const props = defineProps<{
  modelValue?: string | number
  disabled?: boolean
  defaultValue?: string | number | null
  class?: HTMLAttributes['class']
  maxlength?: number
}>()

const emits = defineEmits<{
  (e: 'update:modelValue', payload: string | number): void
}>()

const modelValue = useVModel(props, 'modelValue', emits, {
  passive: true,
  defaultValue: props.defaultValue ?? undefined,
})
</script>

<template>
  <div>
    <textarea
      v-model="modelValue"
      v-bind="$attrs"
      :disabled="disabled"
      :maxlength="props.maxlength"
      :class="cn(
        'text-sm rounded-md px-3 py-3 duration-300 active:scale-99 focus:outline-none',
        'placeholder:text-slate-950/50 dark:placeholder:text-slate-100/40 text-active',
        'w-full border-none bg-field',
        'focus:ring active:ring-blue-600 focus:ring-blue-600/90',
        'aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive',
        props.class
      )"
      rows="5"
    ></textarea>

    <div v-if="props.maxlength" class="text-sm text-end text-body-muted mt-1">
      {{ String(modelValue).length }} / {{ props.maxlength }} characters
    </div>
  </div>
</template>
