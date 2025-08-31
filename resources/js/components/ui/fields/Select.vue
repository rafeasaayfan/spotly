<!-- components/ui/fields/Select.vue -->
<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { ChevronDown } from 'lucide-vue-next';

const props = defineProps<{
    modelValue: string | number | null
    class?: HTMLAttributes['class']
    parentClass?: HTMLAttributes['class']
    name?: string
    id?: string
    placeholder?: string | null
}>()

const emit = defineEmits<{
    (e: 'update:modelValue', value: string | number): void
}>()

function updateValue(event: Event) {
    const target = event.target as HTMLSelectElement
    emit('update:modelValue', target.value)
}
</script>

<template>
    <div :class="cn('relative w-full flex items-center bg-field border border-muted rounded-md cursor-pointer duration-150 ease-in-out min-h-5 h-9',
        props.parentClass)">
        <select :name="props.name" :id="props.id" :value="modelValue" @change="updateValue" :class="cn(
            'appearance-none border-none rounded-md cursor-pointer duration-150 ease-in-out',
            'placeholder:text-slate-800 dark:placeholder:text-slate-200 text-active text-sm',
            'w-full ps-3 h-full bg-field',
            'focus:ring active:ring-blue-500 focus:ring-blue-600/90 outline-none',
            props.class
        )
            ">
            <option disabled value="" selected hidden>
                {{ props.placeholder ?? 'Select an option' }}
            </option>
            <slot />
        </select>

        <!-- Custom arrow inside the select -->
        <div class="pointer-events-none absolute end-1.5 top-0 h-full flex items-center justify-center">
            <ChevronDown class="size-3" />
        </div>
    </div>
</template>
