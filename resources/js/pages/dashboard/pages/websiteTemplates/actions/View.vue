<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'website_name', label: 'Website Name' },
    { key: 'template_name', label: 'Template Name' },
    { key: 'templateColor_name', label: 'Template Color Name' },
    { key: 'is_active', label: 'Active', type: 'active' },
    { key: 'created_at', label: 'Created At', type: 'date' },
    { key: 'updated_at', label: 'Updated At', type: 'date' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
