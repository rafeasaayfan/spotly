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
        title: 'Track Orders',
        href: '/dashboard/track-orders',
    },
];

const columns = [
    { key: 'order_order_number', label: 'Order Number' },
    { key: 'status_reason', label: 'Status Reason' },
    { key: 'status', label: 'Status', type: 'status' },
    { key: 'created_at', label: 'Date', type: 'date' },
];

const filter = [
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        options: [
            { value: 'pending', label: 'Pending' },
            { value: 'confirmed', label: 'Confirmed' },
            { value: 'delivered', label: 'Delivered' },
            { value: 'rejected', label: 'Rejected' },
            { value: 'cancelled', label: 'Cancelled' },
            { value: 'refunded', label: 'Refunded' },
        ],
    },
];

const props = defineProps<{
    trackOrders: DataTableProps;
    websiteNameAndLogo?: Record<string, string>;
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
    enableDelete: false,
    enableCreate: false,
    enableView: false,
    enableRowsDelete: false,
};
</script>

<template>
    <Head title="Track Orders" />

    <DashboardLayout :breadcrumbs="breadcrumbs" dashboardFor="e-commerce" :websiteNameAndLogo="props.websiteNameAndLogo">
        <DataTable
            :tableData="props.trackOrders"
            :filter="filter"
            :columns="columns"
            path="trackOrders"
            routeName="website.e-commerce.dashboard.trackOrders"
            :tableConditions="tableConditions"
            dashboardFor="e-commerce"
        />
    </DashboardLayout>
</template>
