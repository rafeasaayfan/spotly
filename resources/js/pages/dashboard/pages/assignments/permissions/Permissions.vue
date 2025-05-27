<script setup lang="ts">
import DataTable from '@/components/table/DataTable.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';

import { Head } from '@inertiajs/vue3';

import { type DataTableProps } from '@/composables/dataTable/useDataTable';
import { defaultTableConditions } from '@/lib/dataTable';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Permissions',
        href: '/dashboard/permissions',
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
    permissions: DataTableProps;
}>();

const tableConditions = {
    ...defaultTableConditions,
    enableAssignRoles: true,
};
</script>

<template>
    <Head title="Permissions" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable
            :table-data="props.permissions"
            :columns="columns"
            route-name="dashboard.permissions"
            :table-conditions="tableConditions"
        />
    </DashboardLayout>
</template>
