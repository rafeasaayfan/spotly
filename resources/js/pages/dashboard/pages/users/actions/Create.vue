<script setup lang="ts">
import Create from '@/components/table/actions/Create.vue';


const props = defineProps<{
    countries: Record<string, any>;
}>();

const mappedCountries = props.countries.map((item: Record<string, any>) => ({
    value: item.phone_code,
    label: item.phone_code,
    icon: item.flag,
}));

const columns = [
    { key: 'name', label: 'Name', type: 'text', required: true },
    { key: 'email', label: 'Email', type: 'email', required: true },
    { key: 'password', label: 'Password', type: 'password', required: true },
    { key: 'password_confirmation', label: 'Confirm Password', type: 'password', required: true },
    { key: 'phone_number', label: 'Phone Number', type: 'phone_number', required: false, options: mappedCountries },
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        options: [
            { value: 'active', label: 'Active' },
            { value: 'inactive', label: 'Inactive' },
            { value: 'banned', label: 'Banned' },
        ],
    },
];
</script>

<template>
    <Create :columns="columns" table="users" />
</template>
