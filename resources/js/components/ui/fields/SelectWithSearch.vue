<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { cn } from '@/lib/utils'
import { ArrowBigDown, ArrowBigDownDash, ArrowBigUpDash } from 'lucide-vue-next';

const props = defineProps<{
  modelValue: string | number | File | null
  options: Array<{ label: string; value: string | number }>
  placeholder?: string
  class?: string
  name?: string
  id?: string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | number): void
}>()

const isOpen = ref(false)
const search = ref('')
const selected = computed(() => props.options.find(option => option.value === props.modelValue)?.label || '')

const filteredOptions = computed(() =>
  props.options.filter(option =>
    option.label.toLowerCase().includes(search.value.toLowerCase())
  )
)

function selectOption(value: string | number) {
  emit('update:modelValue', value)
  isOpen.value = false
  search.value = ''
}

function toggleDropdown() {
  isOpen.value = !isOpen.value
}
</script>

<template>
  <div class="relative w-full" :class="props.class">
    <button
      type="button"
      class="w-full text-start bg-slate-300 hover:bg-slate-200 dark:bg-slate-900 dark:hover:bg-slate-800 text-sm p-2 rounded-md cursor-pointer"
      @click="toggleDropdown"
    >
      <div class="flex items-center justify-between">
        <span>
            {{ selected || props.placeholder || 'Select an option' }}
        </span>
        <ArrowBigDownDash v-if="!isOpen" class="size-4" />
        <ArrowBigUpDash v-if="isOpen" class="size-4" />
      </div>
    </button>

    <div
      v-if="isOpen"
      class="absolute mt-1 z-50 w-full bg-white dark:bg-slate-900 border rounded-md shadow-md max-h-60 overflow-auto"
    >
      <input
        v-model="search"
        type="text"
        placeholder="Search..."
        class="w-full p-2 text-sm bg-zinc-900 border-b border-slate-300 dark:border-slate-700 focus:outline-none"
      />

      <div v-if="filteredOptions.length === 0" class="p-2 text-sm text-slate-500">
        No options found.
      </div>

      <div
        v-for="option in filteredOptions"
        :key="option.value"
        @click="selectOption(option.value)"
        class="cursor-pointer p-2 text-sm hover:bg-slate-200 dark:hover:bg-slate-700"
      >
        {{ option.label }}
      </div>
    </div>
  </div>
</template>
