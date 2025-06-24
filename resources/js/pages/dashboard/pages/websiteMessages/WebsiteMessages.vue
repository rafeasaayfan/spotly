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
        title: 'WebsiteMessages',
        href: '/dashboard/websiteMessages',
    },
];

const columns = [
    { key: 'website_name', label: 'Website Name' },
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email', type: 'email' },
    { key: 'message', label: 'Message' },
    { key: 'created_at', label: 'Created At', type: 'date' },
];

const props = defineProps<{
    websiteMessages: DataTableProps;
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
    enableCreate: false,
    enableEdit: false,
    enableFilter: false,
};
</script>

<template>
    <Head title="WebsiteMessages" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable
            :tableData="props.websiteMessages"
            :columns="columns"
            routeName="dashboard.websiteMessages"
            :tableConditions="tableConditions"
            path="websiteMessages"
        />
    </DashboardLayout>
</template>
