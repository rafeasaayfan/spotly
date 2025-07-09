<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { useVModel } from '@vueuse/core'
import { withDefaults } from 'vue'

const props = withDefaults(defineProps<{
  defaultValue?: string | File | number | null
  modelValue?: any
  class?: HTMLAttributes['class']
}>(), {
  defaultValue: '#000000',
})

const emits = defineEmits<{
  (e: 'update:modelValue', payload: string | number): void
}>()

const modelValue = useVModel(props, 'modelValue', emits, {
  passive: true,
  defaultValue: props.defaultValue ?? '#000000',
})

// Force default if not set
if (!modelValue.value) {
  modelValue.value = props.defaultValue;
}
</script>

<template>
    <input type="color" v-model="modelValue"
    :class="cn(
      'bg-field border border-muted flex h-9 w-full min-w-0 p-0 rounded-md active:scale-98 cursor-pointer',
      'shadow-xs outline-none transition-all duration-150 ease-in-out focus:ring active:ring-blue-500 focus:ring-blue-600/90',
      'disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 text-sm',
      'aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive',
      props.class
    )"
    />
</template>