<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/dashboard/users',
    },
    {
        title: 'Create',
        href: '/dashboard/users/create',
    },
];

interface PropsCreate {
    columns: Array<{ key: string; label: string }>;
}

// const columns = [
//     { key: 'id', label: 'ID' },
//     { key: 'name', label: 'Name' },
//     { key: 'email', label: 'Email' },
//     { key: 'email_verified_at', label: 'Email Verified' },
//     { key: 'created_at', label: 'Created At' },
// ];

const props = defineProps<{PropsCreate}>();

</script>

<template>
    <Head title="Users" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <form class="flex flex-col gap-2 p-4">
        </form>
    </DashboardLayout>
</template>
