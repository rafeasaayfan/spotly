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
        title: 'Website Types',
        href: '/dashboard/website-types',
    },
];

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'user_name', label: 'Created By' },
    { key: 'title', label: 'Title' },
    { key: 'type', label: 'Type' },
    { key: 'priority', label: 'Priority' },
    { key: 'is_active', label: 'Is Active', type: 'toggle' },
];

const filter = [
    {
        key: 'is_active',
        label: 'Is Active',
        type: 'select',
        options: [
            { value: '0', label: 'Inactive' },
            { value: '1', label: 'Active' },
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
    <Head title="Website Types" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable
            :tableData="props.websiteTypes"
            :filter="filter"
            :columns="columns"
            routeName="dashboard.websiteTypes"
            :tableConditions="tableConditions"
            path="websiteTypes"
        />
    </DashboardLayout>
</template>
