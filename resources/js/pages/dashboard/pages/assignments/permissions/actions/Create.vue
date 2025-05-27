<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input, InputError, Textarea } from '@/components/ui/fields';

import Heading from '@/components/headers/Heading.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';

import { type BreadcrumbItem } from '@/types';

import { Head, Link, useForm } from '@inertiajs/vue3';

import { ArrowBigLeft, LoaderCircle } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Assignments',
        href: '/dashboard/assignments/permissions',
    },
];

function submit() {
    form.post(route(`dashboard.permissions.store`));
}

// const form = useForm<Record<string, string | File | null>>(Object.fromEntries(Object.keys(columns).map((key) => [key, ''])));
const form = useForm({
    name: '',
    guard_name: '',
    description: '',
});
</script>

<template>
    <Head title="Permissions" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div class="w-full p-4">
            <Link :href="route('dashboard.permissions.index')">
                <Button variant="secondary" size="sm">
                    <ArrowBigLeft class="size-4" />
                    <p>Go Back</p>
                </Button>
            </Link>
        </div>

        <div class="p-4 mx-4 bg-card rounded-md shadow-md">
            <Heading title="Create new permission" description="Create a new permission for your application." />
        </div>

        <div class="m-4 flex flex-col gap-3 rounded-md">
            <form class="grid gap-4 lg:grid-cols-2" @submit.prevent="submit">
                <div class="grid gap-1">
                    <Label for="name">Name</Label>
                    <Input id="name" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" placeholder="Permission name" />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="grid gap-1">
                    <Label for="guard_name">Guard Name</Label>
                    <Input id="guard_name" class="mt-1 block w-full" v-model="form.guard_name" required autocomplete="name" placeholder="Guard name" />
                    <InputError class="mt-2" :message="form.errors.guard_name" />
                </div>

                <div class="grid gap-1 col-span-2">
                    <Label for="description">Description</Label>
                    <Textarea id="description" class="mt-1 block w-full" v-model="form.description" required placeholder="Permission description" />
                    <InputError class="mt-2" :message="form.errors.description" />
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
