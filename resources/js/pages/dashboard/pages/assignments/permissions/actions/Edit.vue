<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { label: 'Name', key: 'name', type: 'text', required: true },
    { label: 'Guard Name', key: 'guard_name', type: 'text', required: true },
    { label: 'Description', key: 'description', type: 'textarea', required: true },
];
</script>

<template>
    <Edit :data="props.data" :columns="columns" table="permissions" />
</template>
