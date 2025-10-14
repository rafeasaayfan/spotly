<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'parent_id', label: 'Parent Category' },
    { key: 'name', label: 'Name' },
    { key: 'ar_name', label: 'Arabic Name' },
    { key: 'is_active', label: 'Is Active', type: 'active' },
    { key: 'is_in_home', label: 'Is In Home', type: 'active' },
    { key: 'description', label: 'Description', type: 'textarea' },
    { key: 'created_at', label: 'Created At', type: 'date' },
    { key: 'updated_at', label: 'Updated At', type: 'date' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
