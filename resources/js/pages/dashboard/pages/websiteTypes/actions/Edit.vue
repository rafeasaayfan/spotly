<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'type', label: 'Type', type: 'text', placeholder: 'Enter the website type', required: true },
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
    <Edit :data="props.data" :columns="columns" table="websiteTypes" />
</template>
