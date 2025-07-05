<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'website_name', label: 'Website Name' },
    { key: 'name', label: 'Name' },
    { key: 'description', label: 'Description', type: 'textarea' },
    { key: 'is_active', label: 'Active', type: 'active' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
