<script setup lang="ts">
import DataTable from '@/components/table/DataTable.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';

import { Head } from '@inertiajs/vue3';
import { watchEffect } from 'vue';

import { type DataTableProps } from '@/composables/dataTable/useDataTable';
import { defaultTableConditions } from '@/lib/dataTable';
import { toast } from '@/lib/sweetAlert';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Assignments',
        href: '/dashboard/assignments/user-assignments',
    },
];

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'roles_name', label: 'Role' },
    { key: 'permissions_name', label: 'Permission' },
];

const props = defineProps<{
    data: DataTableProps;
    flash?: {
        toastType: 'success' | 'error' | 'warning' | 'info',
        message: string,
    }
}>();

watchEffect(() => {
    const message = props.flash?.message;
    if (message) {
        toast.fire({ icon: props.flash?.toastType, title: message });
    }
});

const tableConditions = {
    ...defaultTableConditions,
    enableFilter: false,
    enableCreate: false,
    enableEdit: false,
    enableView: false,
    enableDelete: false,
    enableRowsDelete: false,
    enableUserAssignments: true,
};
</script>

<template>
    <Head title="User assignments" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable
            :tableData="props.data"
            :columns="columns"
            routeName="dashboard.userAssignments"
            :tableConditions="tableConditions"
            path="assignments/userAssignments"
        />
    </DashboardLayout>
</template>
