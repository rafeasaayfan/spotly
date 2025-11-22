<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter the color name', required: true },
    { key: 'ar_name', label: 'AR Name', type: 'text', placeholder: 'Enter the color name in arabic', required: true },
    { key: 'code', label: 'Code', type: 'color', placeholder: 'Enter the color code', required: true },
];
</script>

<template>
    <Edit :data="props.data" :columns="columns" table="colors" />
</template>
