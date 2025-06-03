<script setup lang="ts">
import Create from '@/components/table/actions/Create.vue';

const columns = [
{ key: 'name', label: 'Name', type: 'text', required: true },
{ key: 'email', label: 'Email', type: 'email', required: true },
{ key: 'password', label: 'Password', type: 'password', required: true },
];
</script>

<template>
    <Create :columns="columns" table="users" href="users" />
</template>
