<script setup lang="ts">
import Create from '@/components/table/actions/Create.vue';

const columns = [
    { key: 'name', label: 'Name', type: 'text', required: true },
    {
        key: 'guard_name',
        label: 'Guard Name',
        type: 'select',
        placeholder: 'Select guard',
        required: true,
        options: [
            { value: 'web', label: 'web' },
            { value: 'website', label: 'website' },
        ],
    },
    { key: 'description', label: 'Description', type: 'textarea', required: true },
];
</script>

<template>
    <Create :columns="columns" table="permissions" />
</template>
