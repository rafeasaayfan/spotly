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
        title: 'Permissions',
        href: '/dashboard/assignments/permissions',
    },
];

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'name', label: 'Name' },
    { key: 'guard_name', label: 'Guard Name' },
    { key: 'description', label: 'Description' },
    { key: 'created_at', label: 'Created At' },
];

const props = defineProps<{
    permissions: DataTableProps;
    flash?: {
        message?: string;
    };
}>();

watchEffect(() => {
    const message = props.flash?.message;
    if (message) {
        toast.fire({ icon: 'success', title: message });
    }
});

const tableConditions = {
    ...defaultTableConditions,
    enableAssignRoles: true,
    enableFilter: false,
};
</script>

<template>
    <Head title="Permissions" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable :tableData="props.permissions" :columns="columns" routeName="dashboard.permissions" :tableConditions="tableConditions" />
    </DashboardLayout>
</template>
