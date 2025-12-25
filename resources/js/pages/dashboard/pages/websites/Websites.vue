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
        title: 'Websites',
        href: '/dashboard/websites',
    },
];

const columns = [
    { key: 'owner_name', label: 'Owner' },
    { key: 'websiteType_type', label: 'Type' },
    { key: 'approvedOrDeniedBy_name', label: 'Viewed By' },
    { key: 'subdomain', label: 'Sub Domain' },
    { key: 'email', label: 'Email', type: 'email' },
    { key: 'is_active', label: 'Active', type: 'toggle' },
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        options: [
            { value: 'pending', label: 'Pending' },
            { value: 'denied', label: 'Denied' },
            { value: 'approved', label: 'Approved' },
        ],
    },
    { key: 'created_at', label: 'Created At', type: 'date' },
];

const filter = [
    {
        key: 'is_active',
        label: 'Active',
        type: 'select',
        placeholder: 'Select active status',
        options: [
            { value: '0', label: 'Inactive' },
            { value: '1', label: 'Active' },
        ],
    },
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        placeholder: 'Select status',
        options: [
            { value: 'pending', label: 'Pending' },
            { value: 'denied', label: 'Denied' },
            { value: 'approved', label: 'Approved' },
        ],
    },
];

const props = defineProps<{
    websites: DataTableProps;
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
    <Head title="Websites" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable
            :tableData="props.websites"
            :filter="filter"
            :columns="columns"
            routeName="dashboard.websites"
            :tableConditions="tableConditions"
            path="websites"
        />
    </DashboardLayout>
</template>
