<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { File, Input, InputError, Select, SelectWithSearch, Textarea } from '@/components/ui/fields';

import Heading from '@/components/headers/Heading.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';

import { type BreadcrumbItem } from '@/types';

import { Head, Link, useForm } from '@inertiajs/vue3';

import { Label } from '@/components/ui/label';
import { ArrowBigLeft, LoaderCircle } from 'lucide-vue-next';

const props = defineProps<{
    data: Record<string, any>;
    columns: Array<{
        key: string;
        label: string;
        type: string;
        placeholder?: string;
        relation?: Array<{ value: string | number; label: string }>;
        options?: Array<{ label: string; value: string | number }>;
        required?: boolean;
    }>;
    table: string;
    href?: string;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: props.table.charAt(0).toUpperCase() + props.table.slice(1),
        href: `/dashboard/${props.href ? props.href : props.table}`,
    },
];

function submit() {
    form.put(route(`dashboard.${props.table}.update`, props.data.id));
}

const form = useForm({
    ...props.data,
});
</script>

<template>
    <Head :title="props.table.charAt(0).toUpperCase() + props.table.slice(1)" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div class="w-full p-4">
            <Link :href="route(`dashboard.${props.table}.index`)">
                <Button variant="secondary" size="sm">
                    <ArrowBigLeft class="size-4" />
                    <p>Go Back</p>
                </Button>
            </Link>
        </div>

        <div class="bg-card mx-4 rounded-md p-4 shadow-md">
            <Heading :title="`Create new ${props.table}`" :description="`Create a new ${props.table} for your application.`" />
        </div>

        <div class="m-4 flex flex-col gap-3 rounded-md">
            <form class="grid gap-4 lg:grid-cols-2" @submit.prevent="submit">
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

                    <InputError class="mt-2" :message="form.errors?.[column.key]" />
                </div>

                <div class="flex justify-end lg:col-span-2">
                    <Button type="submit" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        <span>Create</span>
                    </Button>
                </div>
            </form>
        </div>
    </DashboardLayout>
</template>
