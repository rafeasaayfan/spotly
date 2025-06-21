<script setup lang="ts">
import Create from '@/components/table/actions/Create.vue';

const columns = [
    { label: 'Name', key: 'name', type: 'text', required: true },
    { label: 'Guard Name', key: 'guard_name', type: 'text', required: true },
    { label: 'Description', key: 'description', type: 'textarea', required: true },
];
</script>

<template>
    <Create :columns="columns" table="permissions" />
</template>
