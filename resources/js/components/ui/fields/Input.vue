<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { useVModel } from '@vueuse/core'

const props = defineProps<{
  defaultValue?: string | File | number | null
  modelValue?: any
  class?: HTMLAttributes['class']
}>()

const emits = defineEmits<{
  (e: 'update:modelValue', payload: string | number): void
}>()

const modelValue = useVModel(props, 'modelValue', emits, {
  passive: true,
  defaultValue: props.defaultValue,
})
</script>

<template>
  <input
    v-model="modelValue"
    data-slot="input"
    :class="cn(
      'bg-gray-200 dark:bg-gray-900 border border-muted flex h-9 w-full min-w-0 rounded-md px-3 py-1 active:scale-98',
      'shadow-xs transition-[color,box-shadow] outline-none transition-all focus:ring active:ring-blue-500 focus:ring-blue-600/90',
      'disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 text-sm',
      'aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive',
      props.class,
    )"
  >
</template>
