<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'name', label: 'Name' },
    { key: 'code', label: 'Method Code' },
    { key: 'description', label: 'Description', type: 'textarea' },
    { key: 'settings', label: 'Settings', type: '' },
    { key: 'sort_order', label: 'Sort Order', },
    { key: 'is_active', label: 'Active', type: 'active' },
    { key: 'created_at', label: 'Created At', type: 'date' },
    { key: 'updated_at', label: 'Updated At', type: 'date' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
