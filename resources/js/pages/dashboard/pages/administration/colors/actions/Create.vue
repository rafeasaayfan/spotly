<script setup lang="ts">
import Create from '@/components/table/actions/Create.vue';

const columns = [
    { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter the color name', required: true },
    { key: 'ar_name', label: 'AR Name', type: 'text', placeholder: 'Enter the color name in arabic', required: true },
    { key: 'code', label: 'Code', type: 'color', placeholder: 'Enter the color code', required: true },
];
</script>

<template>
    <Create :columns="columns" table="colors" />
</template>
