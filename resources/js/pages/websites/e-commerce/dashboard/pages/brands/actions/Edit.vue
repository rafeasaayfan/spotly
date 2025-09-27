<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter the brand name', required: true },
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
    { key: 'description', label: 'Description', type: 'textarea', placeholder: 'Describe this brand', required: true },
];
</script>

<template>
    <Edit :data="props.data" :columns="columns" table="brands" routeDash="website.e-commerce.dashboard.brands.update" />
</template>
