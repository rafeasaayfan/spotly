<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';

const props = defineProps<{
    countries: Record<string, any>;
    data: Record<string, any>;
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
];
</script>

<template>
    <Edit :data="props.data" :columns="columns" table="users" routeDash="website.restaurant.dashboard.users.update" />
</template>
