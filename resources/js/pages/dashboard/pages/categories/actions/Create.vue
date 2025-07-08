<script setup lang="ts">
import Create from '@/components/table/actions/Create.vue';

const props = defineProps<{
    websites: Record<string, any>[];
    parents: Record<string, any>[];
}>();

const mappedWebsites = props.websites.map((item: any) => ({
    value: item.id,
    label: item.name,
}));

const mappedParents = props.parents.map((item: any) => ({
    value: item.id,
    label: item.name,
}));

const columns = [
    { key: 'website_id', label: 'Website Name', type: 'select_with_search', placeholder: 'select website', required: true, relation: mappedWebsites },
    {
        key: 'parent_id',
        label: 'Parent Category',
        type: 'select_with_search',
        placeholder: 'select parent category',
        required: true,
        relation: mappedParents,
    },
    { key: 'name', label: 'Category Name', type: 'text', placeholder: 'enter category name', required: true },
    {
        key: 'is_active',
        label: 'Status',
        type: 'select',
        placeholder: 'select status',
        required: true,
        options: [
            { label: 'Active', value: '1' },
            { label: 'Inactive', value: '0' },
        ],
    },
    { key: 'description', label: 'Description', type: 'textarea', placeholder: 'enter description', required: true },
];
</script>

<template>
    <Create :columns="columns" table="categories" />
</template>
