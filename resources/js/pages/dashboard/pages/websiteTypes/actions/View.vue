<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'user_name', label: 'Created By' },
    { key: 'type', label: 'Type' },
    { key: 'is_active', label: 'Status', type: 'boolean' },
    { key: 'description', label: 'Description', type: 'textarea' },
    { key: 'created_at', label: 'Created At', type: 'date' },
    { key: 'updated_at', label: 'Update At', type: 'date' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
