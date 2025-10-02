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
        title: 'Carts',
        href: '/dashboard/carts',
    },
];

const columns = [
    { key: 'websiteUser_name', label: 'User' },
    { key: 'items_count', label: 'Cart Items Count' },
    { key: 'status', label: 'Status', type: 'status' },
    { key: 'expires_at', label: 'Expires At', type: 'date' },
    { key: 'created_at', label: 'Created At', type: 'date' },
];

const filter = [
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        options: [
            { value: 'pending', label: 'Pending' },
            { value: 'checked_out', label: 'Checked Out' },
            { value: 'abandoned', label: 'Abandoned' },
        ],
    },
];

const props = defineProps<{
    carts: DataTableProps;
    websiteNameAndLogo?: Record<string, string>
    flash?: {
        toastType: 'success' | 'error' | 'warning' | 'info';
        message: string;
    };
}>();

watchEffect(() => {
    const message = props.flash?.message;
    if (message) {
        toast.fire({ icon: props.flash?.toastType, title: message });
    }
});

const tableConditions = {
    ...defaultTableConditions,
    enableEdit: false,
    enableCreate: false,
};
</script>

<template>
    <Head title="Carts" />

    <DashboardLayout :breadcrumbs="breadcrumbs" dashboardFor="e-commerce" :websiteNameAndLogo="props.websiteNameAndLogo">
        <DataTable
            :tableData="props.carts"
            :filter="filter"
            :columns="columns"
            routeName="website.e-commerce.dashboard.carts"
            :tableConditions="tableConditions"
            path="carts"
            dashboardFor="e-commerce"
        />
    </DashboardLayout>
</template>
