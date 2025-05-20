<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/fields';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowBigLeft } from 'lucide-vue-next';

const props = defineProps<{
    data: Record<string, any>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/dashboard/users',
    },
];

const form = useForm({ ...props.data });

function submit() {
    form.put(route('dashboard.users.update', props.data.id));
}

const columns = {
    name: {
        type: 'text',
        label: 'Name',
    },
    email: {
        type: 'email',
        label: 'Email',
    },
    password: {
        type: 'password',
        label: 'Password',
    },
    password_confirmation: {
        type: 'password',
        label: 'Password Confirmation',
    },
};
</script>

<template>
    <Head title="Users" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div class="w-full p-4">
            <Link :href="route('dashboard.restaurants.index')"
                class="w-fit py-1 px-2 rounded flex items-center gap-1 cursor-pointer bg-blue-600/30 hover:bg-blue-600/50">
                <ArrowBigLeft class="size-4" />
                <p class="text-semibold text-sm">Go Back</p>
            </Link>
        </div>

        <div class="m-4 flex flex-col gap-3 rounded-md border bg-zinc-950 px-8 py-4 shadow-md">
            <h3>Edit user data</h3>

            <form @submit.prevent="submit" class="grid gap-4 lg:grid-cols-2">
                <div v-for="(config, field) in columns" :key="field">
                    <label class="mb-1 block font-medium text-white">{{ config.label }}</label>

                    <template v-if="config.type === 'text' || config.type === 'email' || config.type === 'password'">
                        <Input :type="config.type" v-model="form[field]" class="input" />
                    </template>

                    <div v-if="form.errors[field]" class="mt-1 text-sm text-red-500">
                        {{ form.errors[field] }}
                    </div>
                </div>

                <div class="flex justify-end lg:col-span-2">
                    <Button :disabled="form.processing">Update</Button>
                </div>
            </form>
        </div>
    </DashboardLayout>
</template>
