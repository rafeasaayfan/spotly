<script setup lang="ts">
import Create from '@/components/table/actions/Create.vue';

const columns = [
    { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter the method name', required: true },
    { key: 'code', label: 'Method Code', type: 'text', placeholder: 'Enter the method code', required: true },
    { key: 'sort_order', label: 'Sort Order', type: 'number', placeholder: 'Method sort order', required: true },
    {
        key: 'is_active',
        label: 'Active',
        type: 'select',
        placeholder: 'Select the status',
        required: true,
        options: [
            { value: '0', label: 'Inactive' },
            { value: '1', label: 'Active' },
        ],
    },
    {
        key: 'description',
        label: 'Description',
        type: 'textarea',
        placeholder: 'Describe the method',
        required: false,
        maxlength: 150,
    },
];
</script>

<template>
    <Create :columns="columns" table="paymentMethods" />
</template>
