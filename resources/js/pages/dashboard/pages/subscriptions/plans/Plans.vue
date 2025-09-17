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
        title: 'Plans',
        href: '/dashboard/plans',
    },
];

const columns = [
    { key: 'name', label: 'Name' },
    { key: 'price', label: 'Price' },
    { key: 'currency', label: 'Currency' },
    { key: 'duration', label: 'Duration' },
    { key: 'features', label: 'Features' },
    { key: 'is_active', label: 'Is Active', type: 'toggle' },
    { key: 'created_at', label: 'Created At', type: "date" },
];

const filter = [
    {
        key: 'currency',
        label: 'Currency',
        type: 'select',
        options: [
            { value: 'USD', label: 'USD' },
            { value: 'LBP', label: 'LBP' },
        ],
    },
    {
        key: 'duration',
        label: 'Duration',
        type: 'select',
        options: [
            { value: 'monthly', label: 'Monthly' },
            { value: 'yearly', label: 'Yearly' },
        ],
    },
    {
        key: 'is_active',
        label: 'Active status',
        type: 'select',
        options: [
            { value: '0', label: 'Inactive' },
            { value: '1', label: 'Active' },
        ],
    },
];

const props = defineProps<{
    plans: DataTableProps;
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
};
</script>

<template>
    <Head title="Plans" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable
            :tableData="props.plans"
            :filter="filter"
            :columns="columns"
            routeName="dashboard.plans"
            :tableConditions="tableConditions"
            path="subscriptions/plans"
        />
    </DashboardLayout>
</template>
