<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

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
    <Edit :data="props.data" :columns="columns" table="paymentMethods" />
</template>
