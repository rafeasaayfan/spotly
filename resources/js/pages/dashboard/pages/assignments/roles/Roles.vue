<script setup lang="ts">
import DataTable from '@/components/table/DataTable.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';

import { Head } from '@inertiajs/vue3';

import { type DataTableProps } from '@/composables/dataTable/useDataTable';
import { defaultTableConditions } from '@/lib/dataTable';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Roles',
        href: '/dashboard/roles',
    },
];

const columns = [
    { key: 'id', label: 'Id' },
    { key: 'name', label: 'Name' },
    { key: 'guard_name', label: 'Guard Name' },
    { key: 'description', label: 'Description' },
    { key: 'created_at', label: 'Created At' },
];

const props = defineProps<{
    roles: DataTableProps;
}>();

const tableConditions = {
    ...defaultTableConditions,
    enableAssignPermissions: true,
};
</script>

<template>
    <Head title="Roles" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable :table-data="props.roles" :columns="columns" route-name="dashboard.roles" :table-conditions="tableConditions" />
    </DashboardLayout>
</template>
