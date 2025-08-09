<script setup lang="ts">
import { cn } from '@/lib/utils'
import { useVModel } from '@vueuse/core'
import { computed, type HTMLAttributes } from 'vue'

const props = defineProps<{
  modelValue: string | number | boolean
  value: string | number | boolean
  class?: HTMLAttributes['class']
}>()

const emits = defineEmits<{
  (e: 'update:modelValue', value: string | number | boolean): void
}>()

const model = useVModel(props, 'modelValue', emits)

const isSelected = computed(() => model.value === props.value)
</script>

<template>
  <button
    type="button"
    role="radio"
    :aria-checked="isSelected"
    @click="model = props.value"
    :class="
      cn(
        'cursor-pointer rounded-full border border-blue-600/10 dark:border-blue-600/5 w-[19px] h-[19px] flex justify-center items-center transition-transform duration-150 active:scale-95 focus:outline-none focus:ring-0',
        'bg-[var(--field)]',
        isSelected
          ? 'bg-blue-800 dark:bg-blue-600'
          : 'bg-transparent',
        props.class
      )
    "
  >
    <div
      v-if="isSelected"
      class="w-[9px] h-[9px] rounded-full bg-white"
    ></div>
  </button>
</template>
