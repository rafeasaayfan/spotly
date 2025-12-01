<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'name', label: 'Name', type: 'baseName' },
    { key: 'name_ar', label: 'Arabic Name' },
    { key: 'values_value', label: 'Possible Values', type: 'highlight' },
    { key: 'is_active', label: 'Is active', type: 'baseActive' },
    { key: 'type', label: 'Type', type: '' },
    { key: 'description', label: 'Description', type: 'textarea' },
    { key: 'created_at', label: 'Created At', type: 'date' },
    { key: 'updated_at', label: 'Updated At', type: 'date' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
