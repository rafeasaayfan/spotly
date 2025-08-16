<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

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
    <Edit :data="props.data" :columns="columns" table="roles" />
</template>
