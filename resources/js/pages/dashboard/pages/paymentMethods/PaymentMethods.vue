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
        title: 'Payment Methods',
        href: '/dashboard/payment-methods',
    },
];

const columns = [
    { key: 'name', label: 'Name' },
    { key: 'code', label: 'Method Code' },
    { key: 'description', label: 'Description', type: 'textarea' },
    { key: 'sort_order', label: 'Sort Order' },
    { key: 'is_active', label: 'Active', type: 'toggle' },
    { key: 'created_at', label: 'Created At', type: 'date' },
];

const filter = [
    {
        key: 'is_active',
        label: 'Active',
        type: 'select',
        options: [
            { value: '0', label: 'Inactive' },
            { value: '1', label: 'Active' },
        ],
    },
];

const props = defineProps<{
    paymentMethods: DataTableProps;
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
};
</script>

<template>
    <Head title="PaymentMethods" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable
            :tableData="props.paymentMethods"
            :filter="filter"
            :columns="columns"
            routeName="dashboard.paymentMethods"
            :tableConditions="tableConditions"
            path="paymentMethods"
        />
    </DashboardLayout>
</template>
