<script setup lang="ts">
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue'
import { cn } from '@/lib/utils'
import { ArrowBigDownDash, ArrowBigUpDash } from 'lucide-vue-next';

const props = defineProps<{
  modelValue: string | number | File | null
  options?: Array<{ label: string; value: string | number }>
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
const selected = computed(() => props.options?.find(option => option.value === props.modelValue)?.label || '')

const selectRef = ref<HTMLElement | null>(null)

const filteredOptions = computed(() =>
  props.options?.filter(option =>
    option.label.toLowerCase().includes(search.value.toLowerCase())
  )
)

function handleClickOutside(event: MouseEvent) {
  if (selectRef.value && !selectRef.value.contains(event.target as Node)) {
    isOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
})

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
  <div ref="selectRef" class="relative w-full" :class="props.class">
    <button
      type="button"
      class="w-full text-start border border-muted bg-slate-300 hover:bg-slate-200 dark:bg-slate-950 dark:hover:bg-slate-950 text-sm p-2 rounded-md cursor-pointer"
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
      class="dropdown-scrollbar absolute mt-1 z-50 w-full bg-white dark:bg-slate-900 border border-muted rounded-md shadow-lg max-h-60 overflow-auto"
    >
      <input
        v-model="search"
        type="text"
        placeholder="Search..."
        class="w-full p-2 text-sm bg-slate-200 dark:bg-slate-950 border-b border-blue-700 shadow-lg focus:outline-none"
      />

      <div v-if="filteredOptions?.length === 0" class="p-2 text-sm text-slate-500">
        No options found.
      </div>

      <div
        v-for="option in filteredOptions"
        :key="option.value"
        @click="selectOption(option.value)"
        class="cursor-pointer p-2 text-sm hover:bg-slate-200 dark:hover:bg-slate-950"
      >
        {{ option.label }}
      </div>
    </div>
  </div>
</template>

<style>
/* Scrollbar styles for your dropdown */
.dropdown-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: #64748b #f1f5f9; /* thumb color, track color */
}

/* Chrome, Edge, Safari */
.dropdown-scrollbar::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

.dropdown-scrollbar::-webkit-scrollbar-track {
  background: #f1f5f9; /* track color */
}

.dropdown-scrollbar::-webkit-scrollbar-thumb {
  background-color: #64748b; /* thumb color */
  border-radius: 4px;
  border: 2px solid #f1f5f9; /* to add some padding around thumb */
}

/* Dark mode overrides */
.dark .dropdown-scrollbar {
  scrollbar-color: #94a3b8 #1e293b;
}

.dark .dropdown-scrollbar::-webkit-scrollbar-track {
  background: #1e293b;
}

.dark .dropdown-scrollbar::-webkit-scrollbar-thumb {
  background-color: #94a3b8;
  border: 2px solid #1e293b;
}

</style>
