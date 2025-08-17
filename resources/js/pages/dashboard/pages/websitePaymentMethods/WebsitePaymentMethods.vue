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
        title: 'Website Payment Methods',
        href: '/dashboard/website-methods',
    },
];

const columns = [
    { key: 'website_name', label: 'Website Name' },
    { key: 'paymentMethod_name', label: 'Payment Method' },
    { key: 'is_active', label: 'Active', type: 'toggle' },
    { key: 'created_at', label: 'Created At', type: 'date' },
];

const filter = [
    {
        key: 'is_active',
        label: 'Active',
        type: 'select',
        placeholder: 'Select a status',
        options: [
            { value: '0', label: 'Inactive' },
            { value: '1', label: 'Active' },
        ],
    },
];

const props = defineProps<{
    websitePaymentMethods: DataTableProps;
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
    <Head title="WebsitePaymentMethods" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable
            :tableData="props.websitePaymentMethods"
            :filter="filter"
            :columns="columns"
            routeName="dashboard.websitePaymentMethods"
            :tableConditions="tableConditions"
            path="websitePaymentMethods"
        />
    </DashboardLayout>
</template>
