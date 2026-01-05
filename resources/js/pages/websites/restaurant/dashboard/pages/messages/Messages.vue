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
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'subject', label: 'Subject' },
    { key: 'message', label: 'Message' },
    { key: 'type', label: 'Type', type: 'highlight' },
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
];

const filter = [
    {
        key: 'type',
        label: 'Type',
        type: 'select',
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
        options: [
            { value: 'new', label: 'New' },
            { value: 'read', label: 'Read' },
            { value: 'closed', label: 'Closed' },
        ],
    },
];

const props = defineProps<{
    messages: DataTableProps;
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
    enableEdit: false,
    enableCreate: false,
};
</script>

<template>
    <Head title="Messages" />

    <DashboardLayout :breadcrumbs="breadcrumbs" dashboardFor="restaurant" :websiteNameAndLogo="props.websiteNameAndLogo">
        <DataTable
            :tableData="props.messages"
            :filter="filter"
            :columns="columns"
            routeName="website.restaurant.dashboard.messages"
            :tableConditions="tableConditions"
            path="messages"
            dashboardFor="restaurant"
        />
    </DashboardLayout>
</template>
