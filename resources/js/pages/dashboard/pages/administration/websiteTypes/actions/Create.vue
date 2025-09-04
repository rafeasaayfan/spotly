<script setup lang="ts">
import Create from '@/components/table/actions/Create.vue';

const columns = [
    { key: 'title', label: 'Title', type: 'text', placeholder: 'Enter the website title', required: true },
    { key: 'type', label: 'Type', type: 'text', placeholder: 'Enter the website type', required: true },
    { key: 'priority', label: 'Priority', type: 'number', placeholder: 'Enter the website priority', required: true },
    {
        key: 'is_active',
        label: 'Is Active',
        type: 'select',
        required: true,
        options: [
            { value: '0', label: 'Inactive' },
            { value: '1', label: 'Active' },
        ],
    },
    { key: 'description', label: 'Description', type: 'textarea', placeholder: 'Describe the type', required: true, maxlength: 100 },
];
</script>

<template>
    <Create :columns="columns" table="websiteTypes" />
</template>
