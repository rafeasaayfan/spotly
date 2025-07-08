<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'website_name', label: 'Wevbsite Name', type: 'text' },
    { key: 'parent_name', label: 'Parent Category', type: 'text' },
    { key: 'name', label: 'Category Name', type: 'text' },
    { key: 'is_active', label: 'Status', type: 'active' },
    { key: 'description', label: 'Description', type: 'textarea' },
    { key: 'created_at', label: 'Created At', type: 'date' },
    { key: 'updated_at', label: 'Updated At', type: 'date' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
