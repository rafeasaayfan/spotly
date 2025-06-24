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
    { key: 'message', label: 'Message' },
    { key: 'created_at', label: 'Created At', type: 'date' },
];

const props = defineProps<{
    messages: DataTableProps;
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
    <Head title="Messages" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable :tableData="props.messages" :columns="columns" routeName="dashboard.messages" :tableConditions="tableConditions" path="messages" />
    </DashboardLayout>
</template>
