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

watch([phone, code], () => {
    const codeVal = selectedOption.value?.value ?? code.value;
    const fullNumber = `${codeVal}${phone.value}`;
    emits('update:modelValue', fullNumber);
});
</script>

<template>
    <div class="grid w-full grid-cols-4">
        <SelectWithSearch
            v-model="code"
            placeholder="Code"
            :options="props.options"
            class="col-span-1 rounded-none rounded-s-md border-none"
            classDropdown="p-0.5"
        />
        <Input
            v-model="phone"
            type="tel"
            placeholder="71 4** 8**"
            class="col-span-3 rounded-none rounded-e-md"
        />
    </div>
</template>
