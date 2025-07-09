<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';

const props = defineProps<{
    data: Record<string, any>;
    websiteTypes: Record<string, any>;
}>();

const mappedWebsiteTypes = props.websiteTypes.map((item: any) => ({
    value: item.id,
    label: item.type,
}));

const columns = [
    {
        key: 'website_type_id',
        label: 'Website Type',
        type: 'select_with_search',
        placeholder: 'Select type',
        required: true,
        relation: mappedWebsiteTypes,
    },
    { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter template name', required: true },
    { key: 'description', label: 'Description', type: 'textarea', placeholder: 'Describe the template', required: false },
    {
        key: 'is_active',
        label: 'Active',
        type: 'select',
        placeholder: 'Select status',
        required: true,
        options: [
            { value: '0', label: 'Inactive' },
            { value: '1', label: 'Active' },
        ],
    },
];
</script>

<template>
    <Edit :data="props.data" :columns="columns" table="templates" />
</template>
