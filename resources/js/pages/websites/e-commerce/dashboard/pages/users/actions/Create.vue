<script setup lang="ts">
import Create from '@/components/table/actions/Create.vue';

const props = defineProps<{
    countries: Record<string, any>;
}>();

const mappedCountries = props.countries.map((item: Record<string, any>) => ({
    value: item.phone_code,
    label: item.phone_code,
    src: item.flag,
}));

const columns = [
    { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter the user name', required: true },
    { key: 'email', label: 'Email', type: 'email', placeholder: 'Enter the user email', required: true },
    { key: 'password', label: 'Password', type: 'password', placeholder: 'Enter the user password', required: true },
    {
        key: 'phone_number',
        label: 'Phone Number',
        type: 'phone_number',
        placeholder: 'Enter the user phone number',
        required: true,
        options: mappedCountries,
    },
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        placeholder: 'Enter the user status',
        required: true,
        options: [
            { value: 'active', label: 'Active' },
            { value: 'inactive', label: 'Inactive' },
        ],
    },
    {
        key: 'role',
        label: 'Role',
        type: 'select',
        placeholder: 'Enter the user role',
        required: true,
        options: [
            { value: 'admin', label: 'Admin' },
            { value: 'user', label: 'User' },
        ],
    },
];
</script>

<template>
    <Create :columns="columns" table="users" routeDash="website.e-commerce.dashboard.users.store" />
</template>
