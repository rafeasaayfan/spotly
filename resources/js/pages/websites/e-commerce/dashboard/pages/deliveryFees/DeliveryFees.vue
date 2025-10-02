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
        title: 'Delivery Fees',
        href: '/dashboard/delivery-fees',
    },
];

const columns = [
    { key: 'city', label: 'City' },
    { key: 'amount', label: 'Amount' },
    { key: 'created_at', label: 'Created At', type: 'date' },
];

const props = defineProps<{
    deliveryFees: DataTableProps;
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
    enableFilter: false,
};
</script>

<template>
    <Head title="Delivery Fees" />

    <DashboardLayout :breadcrumbs="breadcrumbs" dashboardFor="e-commerce" :websiteNameAndLogo="props.websiteNameAndLogo">
        <DataTable
            :tableData="props.deliveryFees"
            :columns="columns"
            routeName="website.e-commerce.dashboard.deliveryFees"
            :tableConditions="tableConditions"
            path="deliveryFees"
            dashboardFor="e-commerce"
        />
    </DashboardLayout>
</template>
