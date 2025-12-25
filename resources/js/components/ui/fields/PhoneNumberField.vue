<script setup lang="ts">
import { Input, SelectWithSearch } from '.'
import { watch, ref, onMounted } from 'vue'
import { cn } from '@/lib/utils'

interface Option {
    value: string | number
    label: string
    icon?: string
}

const props = withDefaults(defineProps<{
    options: Option[]
    modelValue: string
    selectedCode?: string

    parentClass?: string
    selectParentClass?: string
    selectClass?: string
    selectDropdownClass?: string
    inputClass?: string
}>(), {
    selectedCode: '+961',
})

const emits = defineEmits<{
    (e: 'update:modelValue', payload: string): void
}>()

const phone = ref('')
const code = ref(props.selectedCode)

onMounted(() => {
    if (props.modelValue) {
        const matchedOption = [...props.options]
            .sort((a: any, b: any) => b.value.length - a.value.length)
            .find((opt: any) => props.modelValue.startsWith(opt.value))

        if (matchedOption) {
            code.value = matchedOption.value as string
            phone.value = props.modelValue.slice(String(matchedOption.value).length)
        }
    }
})

// Sync FROM component → parent
watch([phone, code], () => {
    if (phone.value !== null && phone.value !== '') {
        emits(
            'update:modelValue',
            `${code.value}${phone.value}`
        )

    } else {
        emits('update:modelValue', '')
    }
})
</script>

<template>
    <div :class="cn(
        'w-full grid grid-cols-7 gap-[1px] [direction:ltr]',
        props.parentClass
    )">
        <SelectWithSearch v-model="code" placeholder="Code" :options="props.options"
            :parentClass="cn('col-span-2', props.selectParentClass)"
            :class="cn('rounded-none rounded-s-md', props.selectClass)" :classDropdown="cn(props.selectDropdownClass)"
            :withReset="false" />

        <Input v-model="phone" type="tel" placeholder="71 4** 8**" :class="cn(
            'w-full col-span-5 rounded-none rounded-e-md',
            props.inputClass
        )" />
    </div>
</template>