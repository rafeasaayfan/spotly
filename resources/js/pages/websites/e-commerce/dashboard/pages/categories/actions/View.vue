<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'parent_id', label: 'Parent Category' },
    { key: 'name', label: 'Name' },
    { key: 'description', label: 'Description', type: 'textarea' },
    { key: 'is_active', label: 'Is Active', type: 'active' },
    { key: 'created_at', label: 'Created At', type: 'date' },
    { key: 'updated_at', label: 'Updated At', type: 'date' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
