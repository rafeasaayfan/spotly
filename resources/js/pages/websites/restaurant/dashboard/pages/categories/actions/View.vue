<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'parent_id', label: 'Parent Category' },
    { key: 'name', label: 'Name', type: 'baseName' },
    { key: 'ar_name', label: 'Arabic Name' },
    { key: 'is_active', label: 'Is Active', type: 'baseActive' },
    { key: 'is_in_home', label: 'Is In Home', type: 'boolean' },
    { key: 'description', label: 'Description', type: 'textarea' },
    { key: 'created_at', label: 'Created At', type: 'date' },
    { key: 'updated_at', label: 'Updated At', type: 'date' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
