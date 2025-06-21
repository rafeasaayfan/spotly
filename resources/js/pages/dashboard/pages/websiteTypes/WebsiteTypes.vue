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
        title: 'WebsiteTypes',
        href: '/dashboard/websiteTypes',
    },
];

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'user_name', label: 'Created By' },
    { key: 'type', label: 'Type' },
    { key: 'is_active', label: 'Active' },
    { key: 'created_at', label: 'Created At' },
    { key: 'updated_at', label: 'Updated At' },
];

const filter = [
    {
        key: 'is_active',
        label: 'Active',
        type: 'select',
        options: [
            { value: 'all', label: 'All' },
            { value: '0', label: 'No' },
            { value: '1', label: 'Yes' },
        ],
    },
];

const props = defineProps<{
    websiteTypes: DataTableProps;
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
    <Head title="WebsiteTypes" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable
            :table-data="props.websiteTypes"
            :filter="filter"
            :columns="columns"
            route-name="dashboard.websiteTypes"
            :table-conditions="tableConditions"
        />
    </DashboardLayout>
</template>
