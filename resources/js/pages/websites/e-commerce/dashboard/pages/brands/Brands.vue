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
        title: 'Brands',
        href: '/dashboard/brands',
    },
];

const columns = [
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
    brands: DataTableProps;
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
    <Head title="Brands" />

    <DashboardLayout :breadcrumbs="breadcrumbs" dashboardFor="e-commerce">
        <DataTable
            :tableData="props.brands"
            :filter="filter"
            :columns="columns"
            routeName="website.e-commerce.dashboard.brands"
            :tableConditions="tableConditions"
            path="brands"
            dashboardFor="e-commerce"
        />
    </DashboardLayout>
</template>
