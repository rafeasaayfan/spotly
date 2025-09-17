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
        title: 'Subscriptions',
        href: '/dashboard/subscriptions',
    },
];

const columns = [
    { key: 'user_name', label: 'User Name' },
    { key: 'website_name', label: 'Website Name' },
    { key: 'plan_name', label: 'Plan Name' },
    { key: 'status', label: 'Status' },
    { key: 'start_date', label: 'Start At', type: 'date' },
    { key: 'end_date', label: 'End At', type: 'date' },
    { key: 'created_at', label: 'Created At', type: 'date' },
];

const filter = [
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        options: [
            { value: 'pending', label: 'Pending' },
            { value: 'active', label: 'Active' },
            { value: 'expired', label: 'Expired' },
            { value: 'cancelled', label: 'Cancelled' },
            { value: 'free_trial', label: 'Free Trial' },
        ],
    },
];

const props = defineProps<{
    subscriptions: DataTableProps;
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
    enableView: true,
    enableRowsDelete: false,
};
</script>

<template>
    <Head title="Subscriptions" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable
            :tableData="props.subscriptions"
            :filter="filter"
            :columns="columns"
            routeName="dashboard.subscriptions"
            :tableConditions="tableConditions"
            path="subscriptions/overview"
        />
    </DashboardLayout>
</template>
