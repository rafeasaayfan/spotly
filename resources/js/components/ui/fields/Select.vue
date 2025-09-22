<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { ChevronDown } from 'lucide-vue-next'
import { onClickOutside } from '@vueuse/core'
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  modelValue: string | number | null
  placeholder?: string
  parentClass? : HTMLAttributes['class']
  class?: HTMLAttributes['class']
  dropdownClass?: HTMLAttributes['class']
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | number): void
}>()

const isOpen = ref(false)
const options = ref<{ label: string; value: string | number }[]>([])

// Extract options from slot
const slotEl = ref<HTMLSelectElement | null>(null)

onMounted(() => {
  if (slotEl.value) {
    options.value = Array.from(slotEl.value.options).map((opt) => ({
      label: opt.text,
      value: opt.value,
    }))
  }
})

const selectedLabel = computed(() => {
  const opt = options.value.find(o => o.value == props.modelValue)
  return opt ? opt.label : props.placeholder ?? 'Select an option'
})

function selectOption(option: { label: string; value: string | number }) {
  emit('update:modelValue', option.value)
  isOpen.value = false
}

const buttonRef = ref<HTMLButtonElement | null>(null)
const dropdownRef = ref<HTMLElement | null>(null)
const dropdownYClass = ref('mt-1')
const dropdownXClass = ref('start-0 end-0')

onClickOutside(dropdownRef, () => {
  isOpen.value = false
})

function calculateDropdownPosition() {
  if (!buttonRef.value || !dropdownRef.value) return

  const buttonRect = buttonRef.value.getBoundingClientRect()
  const viewportHeight = window.innerHeight || document.documentElement.clientHeight
  const viewportWidth = window.innerWidth || document.documentElement.clientWidth

  // Vertical positioning
  const spaceBelow = viewportHeight - buttonRect.bottom
  const spaceAbove = buttonRect.top

  if (spaceBelow < 200 && spaceAbove > spaceBelow) { // Arbitrary 200px threshold for dropdown height
    dropdownYClass.value = 'mb-1 bottom-full'
  } else {
    dropdownYClass.value = 'mt-1 top-full'
  }

  // Horizontal positioning
  const spaceRight = viewportWidth - buttonRect.right
  const spaceLeft = buttonRect.left

  if (spaceRight < buttonRect.width && spaceLeft > spaceRight) {
    dropdownXClass.value = 'end-0'
  } else if (spaceLeft < buttonRect.width && spaceRight > spaceLeft) {
    dropdownXClass.value = 'start-0'
  } else {
    dropdownXClass.value = 'start-0 end-0' // Default to full width if ample space
  }
}

watch(isOpen, (newVal) => {
  if (newVal) {
    calculateDropdownPosition()
  }
})
</script>

<template>
  <div :class="cn('relative', props.parentClass)" ref="dropdownRef">
    <!-- Hidden native select -->
    <select
      ref="slotEl"
      class="absolute inset-0 opacity-0 pointer-events-none"
      :value="modelValue"
    >
      <slot />
    </select>

    <!-- Custom trigger -->
    <button
      type="button"
      @click="isOpen = !isOpen"
      ref="buttonRef"
      :class="cn(
        'flex items-center justify-between gap-1 w-full px-2 py-2 text-sm rounded-md border border-muted bg-field cursor-pointer',
        'outline-none focus:ring active:ring-blue-500 focus:ring-blue-600/90',
        props.class
      )"
    >
      <span :class="['Select an option', props.placeholder].includes(selectedLabel) ? 'text-body-muted' : ''">{{ selectedLabel }}</span>
      <ChevronDown class="size-3.5 transition-transform" :class="{ 'rotate-180': isOpen }" />
    </button>

    <!-- Custom dropdown -->
    <ul
      v-if="isOpen"
      :class="cn('absolute border border-muted rounded-md bg-body shadow-lg z-90 min-w-40 max-h-60 overflow-auto p-3 w-full',
        dropdownYClass,
        dropdownXClass,
        props.dropdownClass
      )"
    >
      <li
        v-for="option in options"
        :key="option.value"
        @click="selectOption(option)"
        class="px-3 py-2 text-sm cursor-pointer bg-content-2 text-body hover:font-medium rounded-md"
        :class="{ 'font-semibold text-active-link': option.value == modelValue }"
      >
        {{ option.label }}
      </li>
    </ul>
  </div>
</template>
