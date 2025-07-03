<script setup lang="ts">
import { Input, SelectWithSearch } from '.';
import { watch, ref, computed } from 'vue';

const props = defineProps<{
    options: Array<{ value: string | number; label: string; icon?: string; }>;
    modelValue?: string;
    selectedCode: string;
}>();

const emits = defineEmits<{
    (e: 'update:modelValue', payload: string): void;
    // (e: 'update:selectedCountry', payload: string): void;
}>();

const phone = ref('');
const code = ref(props.selectedCode);

const selectedOption = computed(() =>
    props.options.find((o) => o.label === code.value)
);

watch([phone, code], () => {
    const code = selectedOption.value?.value;
    const fullNumber = `${code} ${phone.value}`;
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
