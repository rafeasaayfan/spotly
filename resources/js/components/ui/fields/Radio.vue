<script setup lang="ts">
import { cn } from '@/lib/utils'
import { useVModel } from '@vueuse/core'
import { Check } from 'lucide-vue-next';
import { computed, type HTMLAttributes } from 'vue'
import Label from '../label/Label.vue';

const props = defineProps<{
  modelValue: string | number | boolean
  value: string | number | boolean
  class?: HTMLAttributes['class']
  label?: string
}>()

const emits = defineEmits<{
  (e: 'update:modelValue', value: string | number | boolean): void
}>()

const model = useVModel(props, 'modelValue', emits)

const isSelected = computed(() => model.value === props.value)
</script>

<template>
  <button type="button" @click="model = props.value" class="flex items-center gap-1 cursor-pointer">
    <div role="radio" :aria-checked="isSelected" :class="cn(
      'cursor-pointer rounded-full border-2 border-muted size-[20px] flex justify-center items-center transition-transform duration-150 active:scale-95 focus:outline-none focus:ring-0',
      'bg-[var(--field)]',
      isSelected
        ? 'bg-primary'
        : 'bg-field',
      props.class
    )
      ">
      <div v-if="isSelected" class="w-full h-full rounded-full bg-primary flex items-center justify-center text-white">
        <Check class="size-3.5" />
      </div>
    </div>

    <Label>{{ props.label }}</Label>
  </button>
</template>
