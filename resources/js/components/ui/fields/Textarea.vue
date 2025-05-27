<script setup>
import { cn } from '@/lib/utils'

const props = defineProps({
    name: { type: String, default: '' },
    modelValue: { type: String, default: '' },
    id: { type: String, default: '' },
    label: { type: String, default: '' },
    disabled: { type: Boolean, default: false },
})

const emit = defineEmits(['update:modelValue'])
</script>

<template>
    <div>
        <label v-if="label" :for="id || name" class="block text-sm text-slate-950/75 dark:text-slate-100/75 mb-1">
            {{ label }}
        </label>

        <textarea :id="id || name" :name="name" :disabled="disabled" :value="modelValue"
            @input="emit('update:modelValue', $event.target.value)" v-bind="$attrs" :class="cn(
                'text-sm rounded-md px-3 py-3 duration-300 focus:border-blue-900 dark:focus:border-blue-600',
                'placeholder:text-slate-800 dark:placeholder:text-slate-200 text-slate-900 dark:text-slate-100',
                'w-full bg-slate-300 hover:bg-slate-300 dark:bg-slate-950 dark:hover:bg-slate-950 border-none',
                props.class
            )
            " rows="5">
        </textarea>
    </div>
</template>
