<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';

const props = defineProps<{
    data: Record<string, any>;
    categories: Record<string, any>;
}>();

const mappedCategories = props.categories.map((item: any) => ({
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
    { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter the category name', required: true },
    { key: 'ar_name', label: 'Arabic Name', type: 'text', placeholder: 'Enter the category arabic name', required: true },
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
    { key: 'description', label: 'Description', type: 'textarea', placeholder: 'Describe this category', required: true },
];
</script>

<template>
    <Edit :data="props.data" :columns="columns" table="categories" routeDash="website.restaurant.dashboard.categories.update" />
</template>
