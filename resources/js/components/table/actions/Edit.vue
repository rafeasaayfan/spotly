<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DialogClose, DialogFooter } from '@/components/ui/dialog';
import { File, Input, InputError, Select, SelectWithSearch, Textarea } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';

import { useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { watch } from 'vue';

import { type Column } from '@/composables/dataTable/useDataTable';

const props = defineProps<{
    data: Record<string, any>;
    columns: Column[];
    table: string;
}>();

const hasPasswordField = props.columns.some((column) => column.key === 'password');

const form = useForm<Record<string, any>>({
  ...props.data,
  ...(hasPasswordField ? { password: '', password_confirmation: '' } : {}),
});

watch(
    () => props.data,
    (newData) => {
        form.defaults({ ...newData });
        form.reset();
    },
    // deep is watch every change inside the variable
    //immediate that mean the watch will be called on the first render
    { deep: true, immediate: true },
);

function submit() {
    form.put(route(`dashboard.${props.table}.update`, props.data.id), {
        onSuccess: () => {
            const closeButton = document.querySelector('[data-slot="dialog-close"]');
            (closeButton as HTMLElement)?.click();
        },
    });
}
</script>

<template>
    <form class="grid gap-4 pb-5 lg:grid-cols-2" @submit.prevent="submit">
        <div class="grid gap-1" :class="column.type === 'textarea' ? 'col-span-2' : ''" v-for="(column, index) in props.columns" :key="index">
            <Label :for="column.label">{{ column.label.charAt(0).toUpperCase() + column.label.slice(1) }}</Label>

            <Input
                v-if="column.type === 'text' || column.type === 'email' || column.type === 'number' || column.type === 'password'"
                v-model="form[column.key]"
                :type="column.type"
                :id="column.label"
                class="mt-1 block w-full"
                :autocomplete="column.type"
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
            <span>Update</span>
        </Button>
    </DialogFooter>
</template>
