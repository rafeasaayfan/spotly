<script setup lang="ts">
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { cn } from '@/lib/utils'
import { ArrowBigDownDash, ArrowBigUpDash } from 'lucide-vue-next';
import { Image } from '../image';

const props = defineProps<{
    modelValue: string | number | null
    options?: Array<{ value: string | number, label: string, icon?: string }>
    placeholder?: string
    class?: string
    classDropdown?: string
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
    <div ref="selectRef" class="relative w-full">
        <button type="button" :class="cn('w-full text-start border border-muted bg-field text-sm px-2 py-1 h-9 rounded-md cursor-pointer active:scale-98 transition duration-150 ease-in-out',
            props.class
        )" @click="toggleDropdown">
            <div class="flex items-center justify-between">
                <span>
                    {{ selected || props.placeholder || 'Select an option' }}
                </span>
                <ArrowBigDownDash v-if="!isOpen" class="size-4" />
                <ArrowBigUpDash v-if="isOpen" class="size-4" />
            </div>
        </button>

        <div v-if="isOpen" :class="cn('dropdown-scrollbar absolute mt-1 z-50 p-2 w-full bg-field border border-muted rounded-md shadow-lg max-h-60 overflow-auto',
            props.classDropdown
        )">
            <input v-model="search" type="text" placeholder="Search..."
                class="w-full p-2 text-sm bg-field border-b border-gray-400 dark:border-gray-700 mb-2 focus:outline-none" />

            <div v-if="filteredOptions?.length === 0" class="p-2 text-sm text-slate-500">
                No options found.
            </div>

            <div v-for="option in filteredOptions" :key="option.value" @click="selectOption(option.value)"
                class="flex items-center gap-2 cursor-pointer p-2 text-sm hover:bg-slate-200 dark:hover:bg-slate-950">
                <Image v-if="option.icon && (option.icon as string)" :src="option.icon" class="size-4 rounded-full" />
                <span>{{ option.label }}</span>
            </div>
        </div>
    </div>
</template>

<style>
/* Scrollbar styles for your dropdown */
.dropdown-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: #64748b #f1f5f9;
    /* thumb color, track color */
}

/* Chrome, Edge, Safari */
.dropdown-scrollbar::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.dropdown-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    /* track color */
}

.dropdown-scrollbar::-webkit-scrollbar-thumb {
    background-color: #64748b;
    /* thumb color */
    border-radius: 4px;
    border: 2px solid #f1f5f9;
    /* to add some padding around thumb */
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
