<script setup lang="ts">
import Create from '@/components/table/actions/Create.vue';

const columns = [
    { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter template name', required: true },
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
    { key: 'description', label: 'Description', type: 'textarea', placeholder: 'Describe the template', required: false },
];
</script>

<template>
    <Create :columns="columns" table="templates" />
</template>
