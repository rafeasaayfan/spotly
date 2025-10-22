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
        title: 'Orders',
        href: '/dashboard/orders',
    },
];

const props = defineProps<{
    orders: DataTableProps;
    websiteNameAndLogo?: Record<string, string>
    cities: Array<string>;
    flash?: {
        toastType: 'success' | 'error' | 'warning' | 'info';
        message: string;
    };
}>();

const columns = [
    { key: 'user_name', label: 'User' },
    { key: 'order_number', label: 'Order Number' },
    { key: 'total_amount', label: 'Total Amount' },
    { key: 'delivery_address', label: 'Delivery Address' },
    { key: 'city', label: 'City' },
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        options: [
            { value: 'pending', label: 'Pending' },
            { value: 'confirmed', label: 'Confirmed' },
            { value: 'delivered', label: 'Delivered' },
            { value: 'cancelled', label: 'Cancelled' },
            { value: 'refunded', label: 'Refunded' },
        ],
    },
    { key: 'status_changed_at', label: 'Status Updated', type: 'date' },
];

const mappedCities = props.cities.map((item: any) => ({
    value: item,
    label: item,
}));

const filter = [
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        options: [
            { value: 'pending', label: 'Pending' },
            { value: 'confirmed', label: 'Confirmed' },
            { value: 'delivered', label: 'Delivered' },
            { value: 'cancelled', label: 'Cancelled' },
            { value: 'refunded', label: 'Refunded' },
        ],
    },
    { key: 'city', label: 'City', type: 'select_with_search', options: mappedCities },
];

watchEffect(() => {
    const message = props.flash?.message;
    if (message) {
        toast.fire({ icon: props.flash?.toastType, title: message });
    }
});

const tableConditions = {
    ...defaultTableConditions,
    enableCreate: false,
};
</script>

<template>
    <Head title="Orders" />

    <DashboardLayout :breadcrumbs="breadcrumbs" dashboardFor="e-commerce" :websiteNameAndLogo="props.websiteNameAndLogo">
        <DataTable
            :tableData="props.orders"
            :filter="filter"
            :columns="columns"
            routeName="website.e-commerce.dashboard.orders"
            :tableConditions="tableConditions"
            path="orders"
            dashboardFor="e-commerce"
        />
    </DashboardLayout>
</template>
