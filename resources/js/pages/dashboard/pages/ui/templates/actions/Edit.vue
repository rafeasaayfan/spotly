<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

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
    <Edit :data="props.data" :columns="columns" table="templates" />
</template>
