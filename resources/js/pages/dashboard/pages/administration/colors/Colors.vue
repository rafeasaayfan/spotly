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
        title: 'Colors',
        href: '/dashboard/administration/colors',
    },
];

const columns = [
    { key: 'name', label: 'Name' },
    { key: 'ar_name', label: 'AR Name' },
    { key: 'code', label: 'Code', type: 'color' },
    { key: 'created_at', label: 'Created At', type: 'date' },
];

const props = defineProps<{
    colors: DataTableProps;
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
    enableFilter: false,
};
</script>

<template>
    <Head title="Colors" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable
            :tableData="props.colors"
            :columns="columns"
            routeName="dashboard.colors"
            :tableConditions="tableConditions"
            path="administration/colors"
        />
    </DashboardLayout>
</template>
