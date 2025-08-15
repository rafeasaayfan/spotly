<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DialogClose, DialogFooter } from '@/components/ui/dialog';
import { File, Input, InputError, Select, SelectWithSearch, Textarea, PhoneNumberField, Color, MultiInput } from '@/components/ui/fields';
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
    form.post(route(`dashboard.${props.table}.update`, props.data.id), {
        onSuccess: () => {
            const closeButton = document.querySelector('[data-slot="dialog-close"]');
            (closeButton as HTMLElement)?.click();
        },
    });
}
</script>

<template>
    <form class="grid gap-4 grid-cols-1 lg:grid-cols-2 pb-5" @submit.prevent="submit" enctype="multipart/form-data">
        <div class="flex flex-col gap-1" :class="['textarea'].includes(column.type ?? '') ? 'lg:col-span-2' : ''" v-for="(column, index) in props.columns" :key="index">
            <Label :for="column.label">{{ column.label.charAt(0).toUpperCase() + column.label.slice(1) }}</Label>

            <Input
                v-if="column.type && ['text', 'email', 'password', 'time', 'datetime', 'date', 'tel', 'number'].includes(column.type)"
                v-model="form[column.key]"
                :type="column.type"
                :id="column.label"
                :placeholder="column.placeholder"
                class="block w-full"
                :autocomplete="column.type"
            />

            <Select
                v-else-if="column.type === 'select'"
                :id="column.label"
                class="block w-full"
                v-model="form[column.key]"
                :placeholder="column.placeholder ?? column.label"
                :required="column.required"
            >
                <option v-for="option in column.options" :key="option.label" :value="option.value">{{ option.label }}</option>
            </Select>

            <Textarea
                v-else-if="column.type === 'textarea'"
                :id="column.label"
                v-model="form[column.key]"
                class="block w-full"
                :placeholder="column.placeholder ?? column.label"
                :required="column.required"
                :maxlength="column.maxlength"
            />

            <File
                v-else-if="column.type === 'file'"
                class="block w-full"
                :id="column.label"
                v-model="form[column.key]"
                :name="column.key"
                :label="column.label"
                :src="typeof form[column.key] === 'string' ? form[column.key] : 'http'"
            />

            <SelectWithSearch
                v-else-if="column.type === 'select_with_search'"
                :id="column.label"
                class="block w-full"
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

            <PhoneNumberField
                v-else-if="column.type === 'phone_number'"
                :id="column.label"
                class="block w-full"
                v-model="form[column.key]"
                :required="column.required"
                :options="column.options ?? []"
            />

            <Color
                v-else-if="column.type === 'color'"
                :id="column.label"
                class="block w-full"
                v-model="form[column.key]"
                :required="column.required"
            />

            <MultiInput
                v-else-if="column.type === 'multiInput'"
                :id="column.label"
                class="block w-full"
                v-model="form[column.key]"
                :label="column.label"
                :placeholder="column.placeholder"
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
