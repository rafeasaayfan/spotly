<script setup lang="ts">
import { Input, SelectWithSearch } from '.';
import { watch, ref, computed } from 'vue';

interface Option {
    value: string | number;
    label: string;
    icon?: string;
}

const props = withDefaults(defineProps<{
    options: Option[];
    modelValue?: string;
    selectedCode?: string;
}>(), {
    selectedCode: '+961',
});

const emits = defineEmits<{
    (e: 'update:modelValue', payload: string): void;
    // (e: 'update:selectedCountry', payload: string): void;
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
    <div class="w-full grid grid-cols-3 sm:grid-cols-6 gap-[1px]">
        <SelectWithSearch
            v-model="code"
            placeholder="Code"
            :options="props.options"
            class="col-span-1 rounded-none rounded-s-md "
            classDropdown="p-0.5"
        />

        <Input
            v-model="phone"
            type="tel"
            placeholder="71 4** 8**"
            class="w-full col-span-2 sm:col-span-5 rounded-none rounded-e-md"
        />
    </div>
</template>
