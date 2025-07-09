<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'createdBy_name', label: 'Created BY' },
    { key: 'websiteType_type', label: 'Website Type' },
    { key: 'name', label: 'Name' },
    { key: 'is_active', label: 'Active', type: 'active' },
    { key: 'description', label: 'Description', type: 'textarea', maxlength: 150 },
    { key: 'created_at', label: 'Created At', type: 'date' },
    { key: 'updated_at', label: 'Updated At', type: 'date' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
