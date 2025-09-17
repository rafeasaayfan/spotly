<script setup lang="ts">
import Create from '@/components/table/actions/Create.vue';

const columns = [
    { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter the plan name', required: true },
    { key: 'price', label: 'Price', type: 'number', placeholder: 'Enter the plan price', required: true },
    {
        key: 'currency',
        label: 'Currency',
        type: 'select',
        placeholder: 'Enter the plan currency',
        required: true,
        options: [
            { value: 'USD', label: 'USD' },
            { value: 'LBP', label: 'LBP' },
        ],
    },
    {
        key: 'duration',
        label: 'Duration',
        type: 'select',
        placeholder: 'Enter the plan duration',
        required: true,
        options: [
            { value: 'monthly', label: 'Monthly' },
            { value: 'yearly', label: 'Yearly' },
        ],
    },
    { key: 'features', label: 'Features', type: 'multiInput', placeholder: 'Enter the plan features', required: true },
    {
        key: 'is_active',
        label: 'Is Active',
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
    <Create :columns="columns" table="plans" />
</template>
