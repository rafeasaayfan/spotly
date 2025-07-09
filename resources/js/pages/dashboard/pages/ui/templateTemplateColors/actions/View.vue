<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
{ key: 'template_name', label: 'Template Name', type: '' },
{ key: 'templateColor_name', label: 'Template Color Name', type: '' },
{ key: 'is_default', label: 'Default', type: 'boolean' },
{ key: 'created_at', label: 'Created At', type: 'date' },
{ key: 'updated_at', label: 'Updated At', type: 'date' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
