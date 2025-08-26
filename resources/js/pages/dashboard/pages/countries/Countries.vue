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
        title: 'Countries',
        href: '/dashboard/countries',
    },
];

const columns = [
    { key: 'flag', label: 'Flag', type: 'image' },
    { key: 'country', label: 'Country' },
    { key: 'country_ar', label: 'Country AR' },
    { key: 'country_fr', label: 'Country FR' },
    { key: 'code', label: 'Code' },
    { key: 'phone_code', label: 'Phone Code' },
    { key: 'region', label: 'Region' },
    { key: 'is_active', label: 'Active', type: 'toggle' },
    { key: 'created_at', label: 'Created At', type: 'date' },
];

const filter = [
    {
        key: 'is_active',
        label: 'Active',
        type: 'select',
        options: [
            { value: '0', label: 'Inactive' },
            { value: '1', label: 'Active' },
        ],
    },
];

const props = defineProps<{
    countries: DataTableProps;
    flash?: {
        toastType: 'success' | 'error' | 'warning' | 'info',
        message: string,
    }
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
    <Head title="Countries" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable
            :tableData="props.countries"
            :filter="filter"
            :columns="columns"
            routeName="dashboard.countries"
            :tableConditions="tableConditions"
            path="countries"
        />
    </DashboardLayout>
</template>
