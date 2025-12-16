<script setup lang="ts">
import { ref, computed, watch, nextTick } from 'vue'
import { onClickOutside } from '@vueuse/core'
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { ArrowBigUpDash, XIcon } from 'lucide-vue-next'
import { Image } from '../image'
import { Input } from '.'
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { SharedData } from '@/types'
import { usePage } from '@inertiajs/vue3'
import Icon from '@/components/Icon.vue'

type Option = {
    value: string | number
    label: string
    src?: string
    icon?: string
    color?: string
}

const props = withDefaults(defineProps<{
    modelValue: string | number | null
    options?: Option[]
    placeholder?: string
    parentClass?: HTMLAttributes['class']
    class?: HTMLAttributes['class']
    classDropdown?: HTMLAttributes['class']
    searchClass?: HTMLAttributes['class']
    name?: string
    id?: string
    withReset?: boolean
}>(), {
    withReset: true,
})

const emit = defineEmits<{
    (e: 'update:modelValue', value: string | number): void
}>()

const isOpen = ref(false)
const search = ref('')
const selectRef = ref<HTMLElement | null>(null)
const buttonRef = ref<HTMLButtonElement | null>(null)
const searchInputRef = ref<HTMLElement | null>(null)

const selected = computed(() =>
    props.options?.find(option => option.value === props.modelValue) || null
)

const filteredOptions = computed(() =>
    props.options?.filter(option =>
        option.label.toLowerCase().includes(search.value.toLowerCase())
    ) || []
)

const filteredOptionsLength = computed(() => filteredOptions.value.length)

// Click outside handler
onClickOutside(selectRef, () => {
    isOpen.value = false
})

// Focus search input when dropdown opens
watch(isOpen, (newVal) => {
    if (newVal) {
        nextTick(() => {
            updateDropdownWidth()
            // Focus search input after a short delay to ensure it's rendered
            setTimeout(() => {
                const input = searchInputRef.value?.querySelector('input') as HTMLInputElement
                if (input) {
                    input.focus()
                }
            }, 100)
        })
    } else {
        // Clear search when closing
        search.value = ''
    }
})

function selectOption(value: string | number) {
    emit('update:modelValue', value)
    isOpen.value = false
    search.value = ''
}

const dropdownWidth = ref<number | null>(null)

// Calculate button width to match dropdown width
const updateDropdownWidth = () => {
    if (buttonRef.value) {
        const width = buttonRef.value.offsetWidth
        dropdownWidth.value = width

        const trySetWidth = (attempts = 0) => {
            if (attempts > 10) return // Max 10 attempts

            const dropdownContent = document.querySelector('.selectWithSearch-dropdown') as HTMLElement
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

const page = usePage<SharedData>()
</script>

<template>
    <div ref="selectRef" :class="cn('relative w-full', props.parentClass)">
        <DropdownMenu :open="isOpen" @update:open="isOpen = $event">
            <DropdownMenuTrigger :as-child="true">
                <button type="button" ref="buttonRef" :id="props.id" :name="props.name" :class="cn(
                    'w-full text-start border border-muted bg-field text-sm px-2 py-1 h-9 rounded-md',
                    'cursor-pointer transition duration-150 ease-in-out',
                    'outline-none focus:ring active:ring-blue-500 focus:ring-blue-600/90',
                    props.class
                )">
                    <div class="flex items-center justify-between gap-2">
                        <span :class="selected ? 'text-active' : 'text-body-muted'" class="truncate">
                            {{ selected?.label || props.placeholder || 'Select an option' }}
                        </span>

                        <div class="flex items-center">
                            <XIcon v-if="modelValue && props.withReset" class="size-3.5 cursor-pointer text-body-muted"
                                @click="emit('update:modelValue', '')" />
                            <ArrowBigUpDash class="size-4 flex-shrink-0 transition-all duration-300 ease-in-out"
                                :class="isOpen ? '' : 'rotate-180'" />
                        </div>
                    </div>
                </button>
            </DropdownMenuTrigger>

            <DropdownMenuContent :align="page.props.lang === 'ar' ? 'end' : 'start'"
                :class="cn('selectWithSearch-dropdown w-full max-h-70', props.classDropdown)">
                <!-- Search Input -->
                <div ref="searchInputRef" class="w-full mb-2.5">
                    <Input :class="['px-2 py-0', props.searchClass]" v-model="search" type="text"
                        :placeholder="$t('search.placeholder')" />
                </div>

                <!-- No Results Message -->
                <div v-if="filteredOptionsLength === 0" class="p-2 text-sm text-body-muted text-center">
                    {{ $t('no.result') }}
                </div>

                <!-- Options List -->
                <div v-for="option in filteredOptions" :key="option.value" @click="selectOption(option.value)" :class="cn(
                    'flex items-center gap-2 cursor-pointer px-3 py-2 text-sm rounded-md',
                    'transition-all duration-100 ease-in-out bg-content-2 text-body',
                    'hover:font-medium',
                    {
                        'font-semibold text-active-link': option.value === props.modelValue
                    }
                )">
                    <Image v-if="option.src" :src="option.src" class="size-4.5 rounded-full flex-shrink-0" />

                    <div v-if="option.color" :style="{ backgroundColor: option.color }"
                        class="size-4.5 rounded-full flex-shrink-0 border border-muted shadow-sm dark:shadow-white/3">
                    </div>

                    <Icon v-if="option.icon" :name="option.icon" class="flex-shrink-0" />

                    <span class="truncate">{{ option.label }}</span>
                </div>
            </DropdownMenuContent>
        </DropdownMenu>
    </div>
</template>