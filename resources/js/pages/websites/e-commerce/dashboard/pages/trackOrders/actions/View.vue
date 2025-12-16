<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'order_number', label: 'Order Number', type: '' },
    { key: 'status_reason', label: 'Status Reason', type: 'textarea' },
    { key: 'status', label: 'Status', type: 'status' },
    { key: 'created_at', label: 'Created At', type: 'date' },
    { key: 'updated_at', label: 'Updated At', type: 'date' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
