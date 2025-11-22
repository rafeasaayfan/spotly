<script setup lang="ts">
import { Input, SelectWithSearch } from '.';
import { watch, ref, computed } from 'vue';
import { cn } from '@/lib/utils'

interface Option {
    value: string | number;
    label: string;
    icon?: string;
}

const props = withDefaults(defineProps<{
    options: Option[];
    modelValue?: string;
    selectedCode?: string;

    parentClass?: string,
    selectParentClass?: string,
    selectClass?: string,
    selectDropdownClass?: string,
    inputClass?: string,
}>(), {
    selectedCode: '+961',
});

const emits = defineEmits<{
    (e: 'update:modelValue', payload: string): void;
}>();

const phone = ref(props.modelValue);
const code = ref(props.selectedCode);

const selectedOption = computed(() =>
    props.options.find((o) => o.value === code.value)
);

if (phone.value?.startsWith(code.value as string)) {
    phone.value = phone.value.slice(String(code.value).length);
}

watch([phone, code], () => {
    const codeVal = selectedOption.value?.value ?? code.value;
    let phoneVal = phone.value ?? '';

    // Remove code from the start of phoneVal if present
    if (phoneVal.startsWith(codeVal as string)) {
        phoneVal = phoneVal.slice(String(codeVal).length);
    }

    const fullNumber = `${codeVal}${phoneVal}`;
    emits('update:modelValue', fullNumber);
});
</script>

<template>
    <div :class="cn('w-full w-full grid grid-cols-7 gap-[1px] [direction:ltr]', props.parentClass)">
        <SelectWithSearch
            v-model="code"
            placeholder="Code"
            :options="props.options"
            :parentClass="cn('col-span-2', props.selectParentClass)"
            :class="cn('rounded-none rounded-s-md', props.selectClass)"
            :classDropdown="cn(props.selectDropdownClass)"
            :withReset="false"
        />

        <Input
            v-model="phone"
            type="tel"
            placeholder="71 4** 8**"
            :class="cn('w-full col-span-5 rounded-none rounded-e-md', props.inputClass)"
        />
    </div>
</template>
