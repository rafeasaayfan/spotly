<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { ref, onMounted, onBeforeUnmount, withDefaults } from 'vue'
import { cn } from '@/lib/utils'
import { useVModel } from '@vueuse/core'
import { ColorPicker } from 'vue-color-kit'
import 'vue-color-kit/dist/vue-color-kit.css'
import { ChevronDown } from 'lucide-vue-next'

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

const showPicker = ref(false)
const triggerRef = ref<HTMLElement | null>(null)
const pickerRef = ref<HTMLElement | null>(null)

function handleColorChange(color: any) {
  if (color.rgba && typeof color.rgba.a !== 'undefined' && color.rgba.a < 1) {
    // Use rgba() string if alpha < 1
    const { r, g, b, a } = color.rgba;
    modelValue.value = `rgba(${r},${g},${b},${a})`;
  } else {
    // Use hex for full opacity
    modelValue.value = color.hex;
  }
}

function handleClickOutside(event: MouseEvent) {
  if (
    triggerRef.value && !triggerRef.value.contains(event.target as Node) &&
    pickerRef.value && !pickerRef.value.contains(event.target as Node)
  ) {
    showPicker.value = false
  }
}

onMounted(() => {
  document.addEventListener('mousedown', handleClickOutside)
})
onBeforeUnmount(() => {
  document.removeEventListener('mousedown', handleClickOutside)
})
</script>

<template>
  <div class="relative w-full">
    <div ref="triggerRef" :class="cn(
      'bg-field border border-muted h-9 w-full min-w-0 rounded-md active:scale-98 cursor-pointer',
      'shadow-xs outline-none transition-all duration-150 ease-in-out focus:ring active:ring-blue-500 focus:ring-blue-600/90',
      'disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 text-sm',
      'aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive',
      props.class
    )" @click="showPicker = !showPicker" tabindex="0">

      <div class="flex items-center justify-between w-full h-full bg-transparent px-2 rounded-md">
        <div class="flex items-center gap-2">
          <span class="size-6 rounded border border-muted" :style="{ background: modelValue }">
          </span>
          <span class="truncate max-w-[120px] block pe-2">{{ modelValue }}</span>
        </div>

        <ChevronDown class="size-4 text-body-muted" />
      </div>


    </div>

    <div v-if="showPicker" ref="pickerRef" class="absolute z-50 mt-1 start-0 w-fit rounded">
      <ColorPicker :color="modelValue" theme="dark" :sucker-hide="false" :show-alpha="true"
        @changeColor="handleColorChange" />
    </div>
  </div>
</template>