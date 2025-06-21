<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'name', label: 'Name', type: 'text', required: true },
    { key: 'email', label: 'Email', type: 'email', required: true },
    { key: 'password', label: 'Password', type: 'password', required: false },
    { key: 'password_confirmation', label: 'Confirm Password', type: 'password', required: false },
];
</script>

<template>
    <Edit :data="props.data" :columns="columns" table="users" />
</template>
