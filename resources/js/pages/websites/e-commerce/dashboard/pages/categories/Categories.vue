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
    { key: 'parent_name', label: 'Parent Category' },
    { key: 'name', label: 'Name' },
    { key: 'description', label: 'Description' },
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
    categories: DataTableProps;
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
};
</script>

<template>
    <Head title="Categories" />

    <DashboardLayout :breadcrumbs="breadcrumbs" dashboardFor="e-commerce" :websiteNameAndLogo="props.websiteNameAndLogo">
        <DataTable
            :tableData="props.categories"
            :filter="filter"
            :columns="columns"
            routeName="website.e-commerce.dashboard.categories"
            :tableConditions="tableConditions"
            path="categories"
            dashboardFor="e-commerce"
        />
    </DashboardLayout>
</template>
