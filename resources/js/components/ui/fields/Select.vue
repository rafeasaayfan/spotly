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
    <div :class="cn('relative w-full', props.parentClass)">
        <select :name="props.name" :id="props.id" :value="modelValue" @change="updateValue" :class="cn(
            'appearance-none bg-field',
            'placeholder:text-slate-800 dark:placeholder:text-slate-200 text-slate-900 dark:text-slate-100 text-sm',
            'w-full p-2 me-1.5 border-none rounded-md cursor-pointer duration-150 ease-in-out',
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
        <div class="pointer-events-none absolute end-1 top-0 h-full flex items-center justify-center">
            <ChevronDown class="size-3" />
        </div>
    </div>
</template>
