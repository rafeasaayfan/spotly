<script setup lang="ts">
import Create from '@/components/table/actions/Create.vue';

const props = defineProps<{
    websites: Record<string, any>;
    countries: Record<string, any>;
}>();

const mappedWebsites = props.websites.map((item: any) => ({
    value: item.id,
    label: item.name,
}));

const mappedCountries = props.countries.map((item: Record<string, any>) => ({
    value: item.phone_code,
    label: item.phone_code,
    icon: item.flag,
}));

const columns = [
    {
        key: 'website_id',
        label: 'Website Name',
        type: 'select_with_search',
        placeholder: 'Select a website',
        required: true,
        relation: mappedWebsites,
    },
    { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter the name', required: true, },
    { key: 'email', label: 'Email', type: 'email', placeholder: 'Enter the email', required: true, },
    { key: 'password', label: 'Password', type: 'password', placeholder: 'Enter the password', required: true, },
    { key: 'password_confirmation', label: 'Confirm Password', type: 'password', required: true },
    {
        key: 'phone_number',
        label: 'Phone Number',
        type: 'phone_number',
        placeholder: 'Enter the phone number',
        required: false,
        options: mappedCountries,
    },
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        placeholder: 'Select a status',
        required: true,
        options: [
            { value: 'active', label: 'Active' },
            { value: 'inactive', label: 'Inactive' },
            { value: 'banned', label: 'Banned' },
        ],
    },
];
</script>

<template>
    <Create :columns="columns" table="websiteUsers" />
</template>
