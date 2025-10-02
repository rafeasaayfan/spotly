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
        title: 'Template Template Colors',
        href: '/dashboard/template-template-colors',
    },
];

const columns = [
    { key: 'uiImages', label: 'UI Images', type: 'images' },
    { key: 'websiteType_type', label: 'Website Type' },
    { key: 'template_name', label: 'Template Name' },
    { key: 'templateColor_name', label: 'Template Color Name' },
    { key: 'created_at', label: 'Created At', type: 'date' },
];

const filter = [
    {
        key: 'is_default',
        label: 'Default',
        type: 'select',
        options: [
            { value: '0', label: 'No' },
            { value: '1', label: 'Yes' },
        ],
    },
];

const props = defineProps<{
    templateTemplateColors: DataTableProps;
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
    <Head title="Template Template Colors" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable
            :tableData="props.templateTemplateColors"
            :filter="filter"
            :columns="columns"
            routeName="dashboard.templateTemplateColors"
            :tableConditions="tableConditions"
            path="ui/templateTemplateColors"
        />
    </DashboardLayout>
</template>
