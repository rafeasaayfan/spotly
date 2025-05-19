<script setup lang="ts">
import DataTable from '@/components/table/DataTable.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';

import { Head } from '@inertiajs/vue3';

import { type DataTableProps } from '@/composables/dataTable/useDataTable';
import { defaultTableConditions } from '@/lib/dataTable';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/dashboard/users',
    },
];

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'email_verified_at', label: 'Email Verified' },
    { key: 'created_at', label: 'Created At' },
];

const props = defineProps<{
    users: DataTableProps;
}>();

const tableConditions = {
    ...defaultTableConditions,
};
</script>

<template>
    <Head title="Users" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable :table-data="props.users" :columns="columns" route-name="dashboard.users" :table-conditions="tableConditions" />
    </DashboardLayout>
</template>
