
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
        title: 'Products',
        href: '/dashboard/products',
    },
];

const columns = [
    { key: 'name', label: 'Name' },
    { key: 'category_name', label: 'Category' },
    { key: 'brand_name', label: 'Brand' },
    { key: 'price', label: 'Price' },
    { key: 'sales_count', label: 'Sales Count' },
    { key: 'variants_sum_stock_quantity', label: 'Stock Quantity' },
    { key: 'is_in_home', label: 'Is In Home', type: 'toggle' },
    { key: 'is_special', label: 'Is Special', type: 'toggle' },
    { key: 'is_active', label: 'Is Active', type: 'toggle' },
];

const filter = [
    { key: 'category_id', label: 'Category', type: 'select_with_search' },
    { key: 'brand_id', label: 'Brand', type: 'select_with_search' },
    {
        key: 'is_in_home',
        label: 'Is In Home',
        type: 'select',
        options: [
            { value: '1', label: 'Yes' },
            { value: '0', label: 'No' },
        ],
    },
    {
        key: 'is_special',
        label: 'Is Special',
        type: 'select',
        options: [
            { value: '1', label: 'Yes' },
            { value: '0', label: 'No' },
        ],
    },
    { key: 'is_active', label: 'Is Active', type: 'select', options: [{ value: '1', label: 'yes' }] },
];

const props = defineProps<{
    products: DataTableProps;
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
    <Head title="Products" />

    <DashboardLayout :breadcrumbs="breadcrumbs" dashboardFor="e-commerce">
        <DataTable
            :tableData="props.products"
            :filter="filter"
            :columns="columns"
            routeName="website.e-commerce.dashboard.products"
            :tableConditions="tableConditions"
            path="products"
            dashboardFor="e-commerce"
        />
    </DashboardLayout>
</template>
