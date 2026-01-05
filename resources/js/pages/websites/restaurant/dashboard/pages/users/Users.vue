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
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'email_verified_at', label: 'Verified' },
    { key: 'phone_number', label: 'Phone Number' },
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        options: [
            { value: 'inactive', label: 'Inactive' },
            { value: 'active', label: 'Active' },
            { value: 'banned', label: 'Banned' },
        ],
    },
    {
        key: 'role',
        label: 'Role',
        type: 'select',
        options: [
            { value: 'owner', label: 'Owner' },
            { value: 'admin', label: 'Admin' },
            { value: 'user', label: 'User' },
        ],
    },
];

const filter = [
    {
        key: 'email_verified_at',
        label: 'Verification',
        type: 'select',
        placeholder: 'Verification status',
        options: [
            { value: 'all', label: 'All' },
            { value: 'empty', label: 'Not verified' },
            { value: 'notEmpty', label: 'Verified' },
        ],
    },
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        placeholder: 'Select status',
        options: [
            { value: 'all', label: 'All' },
            { value: 'active', label: 'Active' },
            { value: 'inactive', label: 'Inactive' },
            { value: 'banned', label: 'Banned' },
        ],
    },
    {
        key: 'role',
        label: 'Role',
        type: 'select',
        placeholder: 'Select role',
        options: [
            { value: 'all', label: 'All' },
            { value: 'owner', label: 'Owner' },
            { value: 'admin', label: 'Admin' },
            { value: 'user', label: 'User' },
        ],
    },
];

const props = defineProps<{
    users: DataTableProps;
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
    <Head title="Users" />

    <DashboardLayout :breadcrumbs="breadcrumbs" dashboardFor="restaurant" :websiteNameAndLogo="props.websiteNameAndLogo">
        <DataTable
            :tableData="props.users"
            :filter="filter"
            :columns="columns"
            routeName="website.restaurant.dashboard.users"
            :tableConditions="tableConditions"
            path="users"
            dashboardFor="restaurant"
        />
    </DashboardLayout>
</template>
