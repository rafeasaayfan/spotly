<script setup lang="ts">
import Create from '@/components/table/actions/Create.vue';

const props = defineProps<{
    categories: Record<string, any>;
}>();

const mappedCategories = props.categories.map((item : any) => ({
   value: item.id,
   label: item.name,
}));

const columns = [
    {
        key: 'parent_id',
        label: 'Parent Category',
        type: 'select_with_search',
        placeholder: 'Select a parent category',
        required: true,
        relation: mappedCategories,
    },
    { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter category name', required: true },
    { key: 'description', label: 'Description', type: 'textarea', placeholder: 'Describe this category', required: true },
    {
        key: 'is_active',
        label: 'Is Active',
        type: 'select',
        placeholder: 'Select the activation status',
        required: true,
        options: [
            { value: '0', label: 'Inactive' },
            { value: '1', label: 'Active' },
        ],
    },
];
</script>

<template>
    <Create :columns="columns" table="categories" routeDash="website.e-commerce.dashboard.categories.store" />
</template>
