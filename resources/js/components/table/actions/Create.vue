<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DialogClose, DialogFooter } from '@/components/ui/dialog';
import { File, Input, InputError, Select, SelectWithSearch, Textarea } from '@/components/ui/fields';

import { useForm } from '@inertiajs/vue3';

import { Label } from '@/components/ui/label';
import { LoaderCircle } from 'lucide-vue-next';

import { type Column } from '@/composables/dataTable/useDataTable';

const props = defineProps<{
    columns: Column[];
    table: string;
}>();

const form = useForm(Object.fromEntries(props.columns.map((column) => [column.key, ''])));

function submit() {
    form.post(route(`dashboard.${props.table}.store`), {
        onSuccess: () => {
            const closeButton = document.querySelector('[data-slot="dialog-close"]');
            (closeButton as HTMLElement)?.click();
        },
    });
}

// const form = useForm<Record<string, string | File | null>>(Object.fromEntries(Object.keys(columns).map((key) => [key, ''])));
</script>

<template>
    <form class="grid gap-4 lg:grid-cols-2 pb-5" @submit.prevent="submit" enctype="multipart/form-data">
        <div class="grid gap-1" :class="column.type === 'textarea' ? 'col-span-2' : ''" v-for="(column, index) in props.columns" :key="index">
            <Label :for="column.label">{{ column.label.charAt(0).toUpperCase() + column.label.slice(1) }}</Label>

            <Input
                v-if="column.type && ['text', 'email', 'password', 'time', 'datetime', 'date', 'tel', 'number'].includes(column.type)"
                v-model="form[column.key]"
                :type="column.type"
                :id="column.label"
                class="mt-1 block w-full"
                :autocomplete="column.type"
                :required="column.required"
            />

            <Select
                v-if="column.type === 'select'"
                :id="column.label"
                class="mt-1 block w-full"
                v-model="form[column.key]"
                :placeholder="column.placeholder ?? column.label"
                :required="column.required"
            >
                <option v-for="option in column.options" :key="option.label" :value="option.value">{{ option.label }}</option>
            </Select>

            <Textarea
                v-if="column.type === 'textarea'"
                :id="column.label"
                v-model="form[column.key]"
                class="mt-1 block w-full"
                :placeholder="column.placeholder ?? column.label"
                :required="column.required"
            />

            <File
                v-if="column.type === 'file'"
                class="mt-1 block w-full"
                :id="column.label"
                v-model="form[column.key]"
                :name="column.key"
                :label="column.label"
            />

            <SelectWithSearch
                v-if="column.type === 'select_with_search'"
                :id="column.label"
                class="mt-1 block w-full"
                v-model="form[column.key]"
                :required="column.required"
                :placeholder="column.label"
                :options="
                    column.relation?.map((option) => ({
                        label: option.label ?? option,
                        value: option.value ?? option,
                    }))
                "
            />

            <InputError :message="form.errors?.[column.key]" />
        </div>
    </form>

    <DialogFooter>
        <DialogClose as-child>
            <Button variant="secondary">Cancel</Button>
        </DialogClose>

        <Button type="submit" :disabled="form.processing" @click="submit()">
            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
            <span>Create</span>
        </Button>
    </DialogFooter>
</template>
