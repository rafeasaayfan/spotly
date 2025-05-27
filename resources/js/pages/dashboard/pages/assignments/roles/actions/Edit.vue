<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input, InputError, Textarea } from '@/components/ui/fields';

import Heading from '@/components/headers/Heading.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';

import { type BreadcrumbItem } from '@/types';

import { Head, Link, useForm } from '@inertiajs/vue3';

import { ArrowBigLeft, LoaderCircle } from 'lucide-vue-next';

const props = defineProps<{
    data: Record<string, any>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Assignments',
        href: '/dashboard/assignments/roles',
    },
];

const form = useForm({
    ...props.data,
});

function submit() {
    form.put(route('dashboard.roles.update', props.data.id));
}

// const form = useForm<Record<string, string | File | null>>(Object.fromEntries(Object.keys(columns).map((key) => [key, ''])));
</script>

<template>
    <Head title="Permissions" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div class="w-full p-4">
            <Link :href="route('dashboard.roles.index')">
                <Button variant="secondary" size="sm">
                    <ArrowBigLeft class="size-4" />
                    <p>Go Back</p>
                </Button>
            </Link>
        </div>

        <div class="bg-card mx-4 rounded-md p-4 shadow-md">
            <Heading title="Create new role" description="Create a new role for your application." />
        </div>

        <div class="m-4 flex flex-col gap-3 rounded-md shadow-md">
            <form class="grid gap-4 lg:grid-cols-2" @submit.prevent="submit">
                <div class="grid gap-1">
                    <Label for="name">Name</Label>
                    <Input id="name" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" placeholder="Role name" />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="grid gap-1">
                    <Label for="guard_name">Guard Name</Label>
                    <Input
                        id="guard_name"
                        class="mt-1 block w-full"
                        v-model="form.guard_name"
                        required
                        autocomplete="name"
                        placeholder="Guard name"
                    />
                    <InputError class="mt-2" :message="form.errors.guard_name" />
                </div>

                <div class="col-span-2 grid gap-1">
                    <Label for="description">Description</Label>
                    <Textarea id="description" class="mt-1 block w-full" v-model="form.description" required placeholder="Role description" />
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
