<!-- components/ui/fields/Select.vue -->
<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { ChevronDown } from 'lucide-vue-next';

const props = defineProps<{
    modelValue: string | number | null
    active?: boolean
    class?: HTMLAttributes['class']
    name?: string
    id?: string
    option?: string | null
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
    <div class="relative">
        <select :name="props.name" :id="props.id" :value="modelValue" @change="updateValue" :class="cn(
            'appearance-none bg-field active:scale-98',
            'placeholder:text-slate-800 dark:placeholder:text-slate-200 text-slate-900 dark:text-slate-100 text-sm',
            'min-w-18 w-full p-2 border-none rounded-md cursor-pointer duration-300',
            'focus:ring active:ring-blue-500 focus:ring-blue-600/90 outline-none',
            props.class
        )
            ">
            <option disabled value="" class="text-xs text-body-muted">{{ props.option ?? 'Select an option' }}</option>
            <slot />
        </select>

        <!-- Custom arrow inside the select -->
        <div class="pointer-events-none absolute end-1 top-1/2 -translate-y-1/2">
            <ChevronDown class="size-3" />
        </div>
    </div>
</template>
