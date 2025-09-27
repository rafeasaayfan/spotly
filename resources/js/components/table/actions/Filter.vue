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

function submit(key: string, val: any) {
    filterForm.value[key] = val;

    Object.keys(filterForm.value).forEach((key) => {
        props.setFormData(key, filterForm.value[key]);
    });

    props.applyFilters({ filter: filterForm });
}

const isResetting = ref(false);

function resetForm() {
    filterForm.value = Object.fromEntries(
        (props.filter ?? []).map((column) => [
            column.key,
            column.type === 'select' ? null : '', 
        ]),
    );

    isResetting.value = true;

    setTimeout(() => {
        props.form.reset();
        isResetting.value = false;
    }, 500);

    nextTick(() => {
        props.applyFilters({ filter: '' });
    });
}

watch(
    () => props.form.data(),
    (newData) => {
        filterForm.value = { ...newData };
    },
    { deep: true },
);

watch(
    filterForm,
    (newVal) => {
        Object.keys(newVal).forEach((key) => {
            if (props.form[key] !== newVal[key]) {
                props.setFormData(key, newVal[key]);
            }
        });
    },
    { deep: true },
);
</script>

<template>
    <form class="grid grid-cols-1 gap-5" enctype="multipart/form-data">
        <div class="grid gap-2" v-for="(column, index) in props.filter" :key="index">
            <Label class="text-xs" :for="column.label">{{ column.label.charAt(0).toUpperCase() + column.label.slice(1) }}</Label>

            <Input
                v-if="column.type === 'text' || column.type === 'email' || column.type === 'number' || column.type === 'password'"
                v-model="filterForm[column.key]"
                :type="column.type"
                :id="column.label"
                :autocomplete="column.type"
                :placeholder="column.placeholder ?? column.label"
                @input="submit"
            />

            <Select
                v-if="column.type === 'select'"
                :id="column.label"
                v-model="filterForm[column.key]"
                :option="column.placeholder ?? null"
                :placeholder="column.placeholder ?? column.label"
                @update:modelValue="(val) => submit(column.key, val)"
            >
                <option v-for="option in column.options" :key="option.label" :value="option.value">{{ option.label }}</option>
            </Select>

            <Textarea
                v-if="column.type === 'textarea'"
                :id="column.label"
                v-model="filterForm[column.key]"
                :placeholder="column.placeholder ?? column.label"
                @input="submit"
            />

            <File
                v-if="column.type === 'file'"
                :id="column.label"
                v-model="filterForm[column.key]"
                :name="column.key"
                :label="column.label"
                @change="submit"
            />

            <SelectWithSearch
                v-if="column.type === 'select_with_search'"
                :id="column.label"
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

        <div class="border-muted mt-1 flex justify-end border-t pt-2">
            <Button variant="secondary" size="sm" type="button" :disabled="isResetting" @click="resetForm()">
                <LoaderCircle v-if="isResetting" class="h-4 w-4 animate-spin" />
                <span v-else>Reset</span>
            </Button>
        </div>
    </form>
</template>
