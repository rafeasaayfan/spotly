<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'name', label: 'Name', type: 'baseName' },
    { key: 'code', label: 'Method Code' },
    { key: 'settings', label: 'Settings', type: 'array' },
    { key: 'sort_order', label: 'Sort Order', },
    { key: 'is_active', label: 'Active', type: 'baseActive' },
    { key: 'description', label: 'Description', type: 'textarea' },
    { key: 'created_at', label: 'Created At', type: 'date' },
    { key: 'updated_at', label: 'Updated At', type: 'date' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
