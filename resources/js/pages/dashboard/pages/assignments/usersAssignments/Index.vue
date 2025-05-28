<script setup lang="ts">
import DataTable from '@/components/table/DataTable.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';

import { Head } from '@inertiajs/vue3';

import { type DataTableProps } from '@/composables/dataTable/useDataTable';
import { defaultTableConditions } from '@/lib/dataTable';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users assignments',
        href: '/dashboard/assignments/usersAssignments',
    },
];

const columns = [
    { key: 'id', label: 'Id' },
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'roles_name', label: 'Role' },
    { key: 'permissions_name', label: 'Permission' },
];

const props = defineProps<{
    data: DataTableProps;
}>();

const tableConditions = {
    ...defaultTableConditions,
    enableCreate: false,
    enableEdit: false,
    enableView: false,
    enableDelete: false,
    enableRowsDelete: false,
    enableUsersAssignments: true,
};
</script>

<template>
    <Head title="Users assignments" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable :table-data="props.data" :columns="columns" route-name="dashboard.usersAssignments" :table-conditions="tableConditions" />
    </DashboardLayout>
</template>
