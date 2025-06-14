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
        title: 'Users',
        href: '/dashboard/users',
    },
];

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'email_verified_at', label: 'Verification' },
    { key: 'created_at', label: 'Created At' },
];

const filter = [
    {
        key: 'email_verified_at',
        label: 'Verification',
        type: 'select',
        options: [
            { value: 'value', label: 'all label' },
            { value: 'value', label: 'notEmpty label' },
            { value: 'value', label: 'Empty label' },
        ],
    },
];

const props = defineProps<{
    users: DataTableProps;
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
    <Head title="Users" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable :table-data="props.users" :filter="filter" :columns="columns" route-name="dashboard.users" :table-conditions="tableConditions" />
    </DashboardLayout>
</template>
