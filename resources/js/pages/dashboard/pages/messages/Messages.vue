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
        title: 'Messages',
        href: '/dashboard/messages',
    },
];

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email', type: 'email' },
    { key: 'subject', label: 'Subject' },
    { key: 'type', label: 'Type', type: 'status' },
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        options: [
            { value: 'new', label: 'New' },
            { value: 'read', label: 'Read' },
            { value: 'closed', label: 'Closed' },
        ],
    },
    { key: 'created_at', label: 'Created At', type: 'date' },
];

const filter = [
    {
        key: 'type',
        label: 'Type',
        type: 'select',
        placeholder: 'Select type',
        options: [
            { value: 'support', label: 'Support' },
            { value: 'suggestion', label: 'Suggestion' },
            { value: 'complaint', label: 'Complaint' },
            { value: 'other', label: 'Other' },
        ],
    },
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        placeholder: 'Select status',
        options: [
            { value: 'new', label: 'New' },
            { value: 'read', label: 'Read' },
            { value: 'closed', label: 'Closed' },
        ],
    },
];

const props = defineProps<{
    messages: DataTableProps;
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
    enableCreate: false,
    enableEdit: false,
};
</script>

<template>
    <Head title="Messages" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable
            :tableData="props.messages"
            :columns="columns"
            routeName="dashboard.messages"
            :tableConditions="tableConditions"
            path="messages"
            :filter="filter"
        />
    </DashboardLayout>
</template>
