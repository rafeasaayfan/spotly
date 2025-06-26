<script setup lang="ts">
import Create from '@/components/table/actions/Create.vue';

const columns = [
    { key: 'flag', label: 'Flag', type: 'file', placeholder: 'Enter the country flag', required: true },
    { key: 'country', label: 'Country', type: 'text', placeholder: 'Enter the country name in english', required: true },
    { key: 'country_ar', label: 'Country AR', type: 'text', placeholder: 'Enter the country name in arabic', required: true },
    { key: 'country_fr', label: 'Country FR', type: 'text', placeholder: 'Enter the country name in frensh', required: true },
    { key: 'code', label: 'Code', type: 'text', placeholder: 'Enter the country code', required: true },
    { key: 'phone_code', label: 'Phone Code', type: 'text', placeholder: 'Enter the country phone code', required: true },
    {
        key: 'is_active',
        label: 'Active Status',
        type: 'select',
        placeholder: 'Enter the country active status',
        required: true,
        options: [
            {
                value: '0',
                label: 'Inactive',
            },
            {
                value: '1',
                label: 'Active',
            },
        ],
    },
];
</script>

<template>
    <Create :columns="columns" table="countries" />
</template>
