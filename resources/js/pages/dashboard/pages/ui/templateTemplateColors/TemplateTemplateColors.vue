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
    { key: 'template_name', label: 'Template Name' },
    { key: 'templateColor_name', label: 'Template Color Name' },
    { key: 'is_default', label: 'Default', type: 'toggle' },
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
    <Head title="TemplateTemplateColors" />

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
