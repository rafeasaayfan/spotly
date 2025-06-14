<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { File, Input, InputError, Select, SelectWithSearch, Textarea } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';

import { type Filter } from '@/types';

import { LoaderCircle } from 'lucide-vue-next';
import { nextTick, ref, watch } from 'vue';

const props = defineProps<{
    filter?: Filter[];
    applyFilters: (overrides: Record<string, any>) => void;
    form: any;
    setFormData: (key: string, value: any) => void;
}>();

const filterForm = ref({ ...props.form.data() });

function submit() {
    Object.keys(filterForm.value).forEach(key => {
        props.setFormData(key, filterForm.value[key]);
    });

    props.applyFilters({ filter: props.form.data() });
}

function resetForm() {
    filterForm.value = Object.fromEntries((props.filter ?? []).map((column) => [column.key, '']));

    props.form.reset();

    nextTick(() => {
        submit();
    });
}

watch(() => props.form.data(), (newData) => {
    filterForm.value = { ...newData };
}, { deep: true });

watch(filterForm, (newVal) => {
    Object.keys(newVal).forEach(key => {
        if (props.form[key] !== newVal[key]) {
            props.setFormData(key, newVal[key]);
        }
    });
}, { deep: true });
</script>

<template>
    <form class="grid grid-cols-1 gap-5" @submit.prevent="submit" enctype="multipart/form-data">
        <div class="grid gap-1" v-for="(column, index) in props.filter" :key="index">
            <Label :for="column.label">{{ column.label.charAt(0).toUpperCase() + column.label.slice(1) }}</Label>

            <Input
                v-if="column.type === 'text' || column.type === 'email' || column.type === 'number' || column.type === 'password'"
                v-model="filterForm[column.key]"
                :type="column.type"
                :id="column.label"
                class="mt-1 block w-full"
                :autocomplete="column.type"
                :placeholder="column.placeholder ?? column.label"
                @input="submit"
            />

            <Select
                v-if="column.type === 'select'"
                :id="column.label"
                class="mt-1 block w-full"
                v-model="filterForm[column.key]"
                :option="column.placeholder ?? null"
                :placeholder="column.placeholder ?? column.label"
                @change="submit"
            >
                <option v-for="option in column.options" :key="option.label" :value="option.value">{{ option.label }}</option>
            </Select>

            <Textarea
                v-if="column.type === 'textarea'"
                :id="column.label"
                v-model="filterForm[column.key]"
                class="mt-1 block w-full"
                :placeholder="column.placeholder ?? column.label"
                @input="submit"
            />

            <File
                v-if="column.type === 'file'"
                class="mt-1 block w-full"
                :id="column.label"
                v-model="filterForm[column.key]"
                :name="column.key"
                :label="column.label"
                @change="submit"
            />

            <SelectWithSearch
                v-if="column.type === 'select_with_search'"
                :id="column.label"
                class="mt-1 block w-full"
                v-model="filterForm[column.key]"
                :placeholder="column.placeholder ?? column.label"
                :options="
                    column.options?.map((option) => ({
                        label: option.label ?? option,
                        value: option.value ?? option,
                    }))
                "
                @change="submit"
            />

            <InputError class="mt-2" :message="props.form.errors?.[column.key]" />
        </div>

        <div class="mt mt-1 flex justify-end">
            <Button variant="secondary" size="sm" type="button" :disabled="props.form.processing" @click="resetForm()">
                <LoaderCircle v-if="props.form.processing" class="h-4 w-4 animate-spin" />
                <span v-else>Reset</span>
            </Button>
        </div>
    </form>
</template>
