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
        title: 'My Payments',
        href: '/dashboard/payments',
    },
];

const columns = [
    { key: 'user_name', label: 'User Name' },
    { key: 'website_name', label: 'Website Name' },
    { key: 'plan_name', label: 'Plan' },
    { key: 'paymentMethod_name', label: 'Payment Method' },
    { key: 'amount', label: 'Amount' },
    { key: 'currency', label: 'Currency' },
    {
        key: 'status',
        label: 'Status',
        type: 'status',
    },
    { key: 'paid_at', label: 'Paid At', type: 'date' },
];

const filter = [
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        options: [
            { value: 'pending', label: 'Pending' },
            { value: 'completed', label: 'Completed' },
            { value: 'failed', label: 'Failed' },
        ],
    },
];

const props = defineProps<{
    payments: DataTableProps;
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
    enableEdit: false,
    enableDelete: false,
    enableCreate: false,
    enableView: false,
    enableRowsDelete: false,
};
</script>

<template>
    <Head title="My Payments" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable dir="ltr"
            :tableData="props.payments"
            :filter="filter"
            :columns="columns"
            routeName="client.payments"
            :tableConditions="tableConditions"
            path=""
        />
    </DashboardLayout>
</template>
