<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input, Textarea } from '@/components/ui/fields';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowBigLeft } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Assignments',
        href: '/dashboard/assignments/permissions',
    },
];

function submit() {
    form.post(route(`dashboard.permissions.store`));
}

const columns = {
    name: {
        type: 'text',
        label: 'Name',
    },
    guard_name: {
        type: 'text',
        label: 'Guard Name',
    },
    description: {
        type: 'textarea',
        label: 'Description',
    },
};

const form = useForm<Record<string, string | File | null>>(
    Object.fromEntries(Object.keys(columns).map((key) => [key, '']))
);
</script>

<template>
    <Head title="Permissions" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div class="w-full p-4">
            <Link :href="route('dashboard.permissions.index')"
                class="w-fit py-1 px-2 rounded flex items-center gap-1 cursor-pointer bg-blue-600/30 hover:bg-blue-600/50">
                <ArrowBigLeft class="size-4" />
                <p class="text-semibold text-sm">Go Back</p>
            </Link>
        </div>

        <div class="m-4 flex flex-col gap-3 rounded-md border bg-zinc-950 px-8 py-4 shadow-md">
            <h3>Create new permission</h3>

            <form class="grid gap-4 lg:grid-cols-2" @submit.prevent="submit">
                <div v-for="(config, field) in columns" :key="field">
                    <label class="mb-1 block font-medium">{{ config.label }}</label>

                    <template v-if="config.type === 'text' || config.type === 'email' || config.type === 'password'">
                        <Input :type="config.type" v-model="form[field]" class="input" />
                    </template>

                    <template v-if="config.type === 'textarea'">
                        <Textarea name="" id="" />
                    </template>

                    <div v-if="form.errors[field]" class="mt-1 text-sm text-red-500">
                        {{ form.errors[field] }}
                    </div>
                </div>

                <div class="flex justify-end lg:col-span-2">
                    <Button :disabled="form.processing">Submit</Button>
                </div>
            </form>
        </div>
    </DashboardLayout>
</template>
