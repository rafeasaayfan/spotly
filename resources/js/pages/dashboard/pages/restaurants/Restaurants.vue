<script setup lang="ts">
import DataTable from '@/components/table/DataTable.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';

import { Head } from '@inertiajs/vue3';

import { type DataTableProps } from '@/composables/dataTable/useDataTable';
import { defaultTableConditions } from '@/lib/dataTable';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Restaurants',
        href: '/dashboard/restaurants',
    },
];

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'user_name', label: 'Owner' },
    { key: 'image', label: 'Image' },
    { key: 'name', label: 'Name' },
    { key: 'phone_number', label: 'Phone Number' },
    { key: 'country', label: 'Country' },
    { key: 'open_start', label: 'Open At' },
    { key: 'close_start', label: 'Close At' },
    { key: 'created_at', label: 'Created At' },
];

const props = defineProps<{
    restaurants: DataTableProps;
}>();

const tableConditions = {
    ...defaultTableConditions,
};
</script>

<template>
    <Head title="Restaurants" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable :table-data="props.restaurants" :columns="columns" route-name="dashboard.restaurants" :table-conditions="tableConditions" />
    </DashboardLayout>
</template>
