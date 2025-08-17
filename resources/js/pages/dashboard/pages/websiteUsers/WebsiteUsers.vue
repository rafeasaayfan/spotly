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
        title: 'Website Users',
        href: '/dashboard/website-users',
    },
];

const columns = [
    { key: 'website_name', label: 'Website Name' },
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email', type: 'email' },
    { key: 'email_verified_at', label: 'Verification' },
    { key: 'phone_number', label: 'Phone Number', type: 'phone_number' },
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        options: [
            { value: 'active', label: 'Active' },
            { value: 'inactive', label: 'Inactive' },
            { value: 'banned', label: 'Banned' },
        ],
    },
    { key: 'created_at', label: 'Created At', type: 'date' },
];

const filter = [
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        placeholder: 'Select status',
        options: [
            { value: 'active', label: 'Active' },
            { value: 'inactive', label: 'Inactive' },
            { value: 'banned', label: 'Banned' },
        ],
    },
    {
        key: 'email_verified_at',
        label: 'Verification',
        type: 'select',
        placeholder: 'Select verification status',
        options: [
            { value: 'all', label: 'All' },
            { value: 'empty', label: 'Not verified' },
            { value: 'notEmpty', label: 'Verified' },
        ],
    },
];

const props = defineProps<{
    websiteUsers: DataTableProps;
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
    <Head title="Website Users" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable
            :tableData="props.websiteUsers"
            :filter="filter"
            :columns="columns"
            routeName="dashboard.websiteUsers"
            :tableConditions="tableConditions"
            path="websiteUsers"
        />
    </DashboardLayout>
</template>
