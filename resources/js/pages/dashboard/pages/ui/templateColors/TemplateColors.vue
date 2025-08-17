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
        title: 'Template Colors',
        href: '/dashboard/ui/template-colors',
    },
];

const columns = [
    { key: 'createdBy_name', label: 'Created By' },
    { key: 'name', label: 'Name' },
    { key: 'bg_body_light', label: 'Bg Body Light', type: 'color' },
    { key: 'bg_nav_light', label: 'Bg Nav Light', type: 'color' },
    { key: 'foreground_light', label: 'Foreground Light', type: 'color' },
    { key: 'is_custom', label: 'Is Custom' },
    { key: 'is_active', label: 'Active', type: 'toggle' },
    { key: 'created_at', label: 'Created At', type: 'date' },
];

const filter = [
    {
        key: 'is_active',
        label: 'Active',
        placeholder: 'Select status',
        type: 'select',
        options:
            [
                { value: '0', label: 'Inactive' },
                { value: '1', label: 'Active' }
            ]
    },
    {
        key: 'is_custom',
        label: 'Custom',
        placeholder: 'Select custom',
        type: 'select',
        options: [
            { value: '0', label: 'By spotly' },
            { value: '1', label: 'By client' }
        ]
    },
];


const props = defineProps<{
    templateColors: DataTableProps;
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

    <Head title="TemplateColors" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <DataTable 
            :tableData="props.templateColors" 
            :filter="filter" :columns="columns"
            routeName="dashboard.templateColors"
             :tableConditions="tableConditions" 
            path=ui/templateColors
        />
    </DashboardLayout>
</template>
