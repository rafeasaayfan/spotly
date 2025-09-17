<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'user_name', label: 'User Name', type: '' },
    { key: 'website_name', label: 'Website Name', type: '' },
    { key: 'plan_name', label: 'Plan Name', type: '' },
    { key: 'status', label: 'Status', type: '' },
    { key: 'start_date', label: 'Start At', type: 'date' },
    { key: 'end_date', label: 'End Date', type: 'date' },
    { key: 'created_at', label: 'Created At', type: 'date' },
    { key: 'updated_at', label: 'Updated At', type: 'date' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
