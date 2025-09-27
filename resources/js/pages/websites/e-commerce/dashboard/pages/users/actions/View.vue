<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
{ key: 'name', label: 'Name', type: '' },
{ key: 'email', label: 'Email', type: 'email' },
{ key: 'email_verified_at', label: 'Verified', type: 'email_verifief' },
{ key: 'phone_number', label: 'Phone Number', type: 'phone_number' },
{ key: 'status', label: 'status', type: 'status' },
{ key: 'role', label: 'Role', type: 'status' },
{ key: 'created_at', label: 'Created At', type: 'date' },
{ key: 'updated_at', label: 'Updated At', type: 'date' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
