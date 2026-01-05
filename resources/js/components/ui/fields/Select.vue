<script setup lang="ts">
import { ref, computed, onMounted, watch, nextTick } from 'vue'
import { ChevronDown, XIcon } from 'lucide-vue-next'
import { onClickOutside } from '@vueuse/core'
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { SharedData } from '@/types'
import { usePage } from '@inertiajs/vue3'
import { formatters } from '@/lib/dataTable'

const props = withDefaults(defineProps<{
  modelValue: string | number | null
  placeholder?: string
  parentClass?: HTMLAttributes['class']
  class?: HTMLAttributes['class']
  dropdownClass?: HTMLAttributes['class']
  dropdownItemClass?: HTMLAttributes['class']
  withStatusColors?: boolean
  withReset?: boolean
}>(), {
  dropdownItemClass: 'bg-content-2',
  withStatusColors: false,
  withReset: true,
})

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
  updateDropdownWidth()
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
const dropdownWidth = ref<number | null>(null)

onClickOutside(dropdownRef, () => {
  isOpen.value = false
})

// Calculate button width to match dropdown width
const updateDropdownWidth = () => {
  if (buttonRef.value) {
    const width = buttonRef.value.offsetWidth
    dropdownWidth.value = width

    const trySetWidth = (attempts = 0) => {
      if (attempts > 10) return // Max 10 attempts

      const dropdownContent = document.querySelector('.select-dropdown') as HTMLElement
      if (dropdownContent) {
        dropdownContent.style.setProperty('min-width', `${width}px`, 'important')
      } else if (attempts < 10) {
        setTimeout(() => trySetWidth(attempts + 1), 50)
      }
    }

    // Start trying after a short delay to let the dropdown render
    setTimeout(() => {
      trySetWidth()
    }, 10)
  }
}

// Update width when dropdown opens
watch(isOpen, (newVal) => {
  if (newVal) {
    updateDropdownWidth()
  }
})

const page = usePage<SharedData>();
</script>

<template>
  <div :class="cn('relative', props.parentClass)" ref="dropdownRef">
    <!-- Hidden native select -->
    <select ref="slotEl" class="absolute inset-0 opacity-0 pointer-events-none" :value="modelValue">
      <slot />
    </select>

    <DropdownMenu :open="isOpen" @update:open="isOpen = $event">
      <DropdownMenuTrigger :as-child="true">
        <!-- Custom trigger -->
        <button type="button" ref="buttonRef" :class="cn(
          'flex items-center justify-between gap-1 w-full px-2 py-2 text-sm rounded-md border border-muted bg-field cursor-pointer',
          'outline-none focus:ring active:ring-blue-500 focus:ring-blue-600/90',
          props.class
        )">
          <span v-if="['Select an option', props.placeholder].includes(selectedLabel)" class="text-body-muted">
            {{ selectedLabel }}
          </span>
          <span v-else-if="props.withStatusColors" v-html="formatters.status(selectedLabel)"></span>
          <span v-else class="text-active">{{ selectedLabel }}</span>

          <div class="flex items-center">
            <XIcon v-if="modelValue && props.withReset" class="size-3.5 cursor-pointer text-body-muted" @click="emit('update:modelValue', '')" />
            <ChevronDown class="size-3.5 transition-transform" :class="{ 'rotate-180': isOpen }" />
          </div>
        </button>
      </DropdownMenuTrigger>

      <DropdownMenuContent :align="page.props.lang === 'ar' ? 'end' : 'start'"
        :class="cn('select-dropdown w-full max-h-70', props.dropdownClass)">
        <!-- Custom dropdown -->
        <div v-for="option in options" :key="option.value" @click="selectOption(option)" :class="cn(
          'flex items-center gap-2 cursor-pointer px-3 py-2 text-sm rounded-md',
          'transition-all duration-100 ease-in-out text-body',
          'hover:font-medium', props.dropdownItemClass,
          {
            'font-semibold text-active-link': option.value === modelValue
          }
        )">
          {{ option.label }}
        </div>
      </DropdownMenuContent>
    </DropdownMenu>
  </div>
</template>
