<script setup lang="ts">
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { cn } from '@/lib/utils'
import { ArrowBigUpDash } from 'lucide-vue-next';
import { Image } from '../image';
import { Input } from '.';

const props = defineProps<{
    modelValue: string | number | null
    options?: Array<{ value: string | number, label: string, icon?: string }>
    placeholder?: string
    parentClass?: string
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
    <div ref="selectRef" :class="cn('relative w-full', props.parentClass)">
        <button type="button" :class="cn('w-full text-start border border-muted bg-field text-sm px-2 py-1 h-9 rounded-md z-5', 
           'cursor-pointer transition duration-150 ease-in-out focus:ring active:ring-blue-500 focus:ring-blue-600/90 text-active',
            props.class
        )" @click="toggleDropdown">
            <div class="flex items-center justify-between">
                <span>
                    {{ selected || props.placeholder || 'Select an option' }}
                </span>
                <ArrowBigUpDash class="size-4 z-0 transition-all duration-300 ease-in-out" :class="isOpen? '' : 'rotate-180'" />
            </div>
        </button>

        <div v-if="isOpen" :class="cn('z-10 absolute mt-1 z-50 p-2 w-full bg-white dark:bg-black rounded-md shadow-lg max-h-60 overflow-auto',
            'border border-muted shadow-md min-w-60',
            props.classDropdown
        )">
            <div class="w-full">
                <Input v-model="search" type="text" :placeholder="$t('search.placeholder')" class="mb-2.5" />
            </div>


            <div v-if="filteredOptions?.length === 0" class="p-2 text-sm text-slate-500">
                {{ $t('no.result') }}
            </div>

            <div v-for="option in filteredOptions" :key="option.value" @click="selectOption(option.value)"
                class="flex items-center gap-2 cursor-pointer px-3 py-2 text-sm rounded-md hover:font-bold
                transition-all duration-100 ease-in-out bg-content-2">
                <Image v-if="option.icon && (option.icon as string)" :src="option.icon" class="size-4 rounded-full" />
                <span>{{ option.label }}</span>
            </div>
        </div>
    </div>
</template>