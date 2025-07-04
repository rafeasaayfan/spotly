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
        title: 'EmailSubscribers',
        href: '/dashboard/emailSubscribers',
    },
];

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'email', label: 'Email', type: 'email' },
    { key: 'created_at', label: 'Created At', type: 'date' },
];

const props = defineProps<{
    emailSubscribers: DataTableProps;
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
    enableFilter: false,
    enableEdit: false,
};
</script>

<template>
    <Head title="EmailSubscribers" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable
            :tableData="props.emailSubscribers"
            :columns="columns"
            routeName="dashboard.emailSubscribers"
            :tableConditions="tableConditions"
            path="emailSubscribers"
        />
    </DashboardLayout>
</template>
