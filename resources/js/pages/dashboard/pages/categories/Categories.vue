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
        title: 'Categories',
        href: '/dashboard/categories',
    },
];

const columns = [
    { key: 'website_name', label: 'Website Name' },
    { key: 'parent_name', label: 'Parent Category' },
    { key: 'name', label: 'Category Name' },
    { key: 'description', label: 'Description' },
    { key: 'is_active', label: 'Status', type: 'toggle' },
    { key: 'created_at', label: 'Created At', type: 'date' },
];

const filter = [
    {
        key: 'is_active',
        label: 'Status',
        type: 'select',
        options: [
            { label: 'Active', value: '1' },
            { label: 'Inactive', value: '0' },
        ],
    },
];

const props = defineProps<{
    categories: DataTableProps;
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
    <Head title="Categories" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable
            :tableData="props.categories"
            :filter="filter"
            :columns="columns"
            routeName="dashboard.categories"
            :tableConditions="tableConditions"
            path="categories"
        />
    </DashboardLayout>
</template>
