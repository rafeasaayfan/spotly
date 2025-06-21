<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'type', label: 'Type', type: 'text', required: true },
    {
        key: 'is_active',
        label: 'Active',
        type: 'select',
        required: true,
        options: [
            { value: '0', label: 'No' },
            { value: '1', label: 'Yes' },
        ],
    },
];
</script>

<template>
    <Edit :data="props.data" :columns="columns" table="websiteTypes" href="websiteTypes" />
</template>
