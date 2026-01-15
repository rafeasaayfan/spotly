<script setup lang="ts">
import DataTable from '@/components/table/DataTable.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';

import { Head } from '@inertiajs/vue3';
import { watchEffect } from 'vue';

import { type ActionTypes, type DataTableProps } from '@/composables/dataTable/useDataTable';
import { defaultTableConditions } from '@/lib/dataTable';
import { toast } from '@/lib/sweetAlert';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Menu Itmes',
        href: '/dashboard/menu-items',
    },
];

const columns = [
    { key: 'restaurant_item_images', label: 'Images', type: 'images' },
    { key: 'category_name', label: 'Category' },
    { key: 'name', label: 'Name' },
    { key: 'price', label: 'Price' },
    { key: 'is_discount', label: 'Is Discount', type: 'toggle' },
    { key: 'is_special', label: 'Is Special', type: 'toggle' },
    { key: 'is_in_home', label: 'In Home', type: 'toggle' },
    { key: 'is_active', label: 'Active', type: 'toggle' },
    { key: 'created_at', label: 'Created At', type: 'date' },
];

const filter = [
{
        key: 'is_active',
        label: 'Is Active',
        type: 'select',
        placeholder: 'Select status',
        options: [
            { value: '1', label: 'Active' },
            { value: '0', label: 'Inactive' },
        ],
    },
];

const props = defineProps<{
    menuItems: DataTableProps;
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

const actionTypes: ActionTypes = {
    isModalForCreate: false,
    isModalForEdit: false,
    isModalForView: false
}
</script>

<template>
    <Head title="Menu Items" />

    <DashboardLayout :breadcrumbs="breadcrumbs" dashboardFor="restaurant" :websiteNameAndLogo="props.websiteNameAndLogo">
        <DataTable
            :tableData="props.menuItems"
            :filter="filter"
            :columns="columns"
            routeName="website.restaurant.dashboard.menuItems"
            :tableConditions="tableConditions"
            path="menuItems"
            dashboardFor="restaurant"
            :actionTypes="actionTypes"
        />
    </DashboardLayout>
</template>
