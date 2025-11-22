<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'website_name', label: 'Website', type: 'baseName' },
    { key: 'name', label: 'Name' },
    { key: 'is_active', label: 'Is active', type: 'baseActive' },
    { key: 'type', label: 'Type', type: '' },
    { key: 'is_required', label: 'Is required', type: 'boolean' },
    { key: 'description', label: 'Description', type: 'textarea' },
    { key: 'created_at', label: 'Created At', type: 'date' },
    { key: 'updated_at', label: 'Updated At', type: 'date' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
