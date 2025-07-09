<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email', type: 'email' },
    { key: 'email_verified_at', label: 'Verified At' },
    { key: 'phone_number', label: 'Phone Number', type: 'phone_number' },
    {
        key: 'status',
        label: 'Status',
        type: 'status',
    },
    { key: 'created_at', label: 'Created At', type: 'date' },
    { key: 'updated_at', label: 'Updated At', type: 'date' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
