<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';

const props = defineProps<{
    data: Record<string, any>;
    users: Array<{ id: any; name: any }>;
    websiteTypes: Array<{ id: any; type: any }>;
}>();

const mappedUsers = props.users.map((item) => ({
    value: item.id,
    label: item.name,
}));

const mappedTypes = props.websiteTypes.map((item) => ({
    value: item.id,
    label: item.type,
}));

const columns = [
    { key: 'logo', label: 'Website logo', type: 'file', placeholder: 'Enter the website logo', required: true },
    { key: 'owner_id', label: 'Website owner', type: 'select_with_search', placeholder: 'Select the owner', required: true, relation: mappedUsers },
    {
        key: 'website_type_id',
        label: 'Website type',
        type: 'select_with_search',
        placeholder: 'Enter the type',
        required: true,
        relation: mappedTypes,
    },
    { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter the website name', required: true },
    { key: 'subdomain', label: 'Sub domain', type: 'text', placeholder: 'Enter the sub domain', required: true },
    { key: 'country', label: 'Country', type: 'select', placeholder: 'Select the country', required: false },
    { key: 'city', label: 'City', type: 'select', placeholder: 'Select the city', required: false },
    { key: 'address', label: 'Address', type: 'text', placeholder: 'Enter the address', required: true },
    {
        key: 'phone_number',
        label: 'Phone Number',
        type: 'text',
        placeholder: 'Enter the website phone number',
        required: true,
    },
    {
        key: 'language',
        label: 'Language',
        type: 'select',
        placeholder: 'Select the website default language',
        required: true,
        options: [
            { value: 'en', label: 'English' },
            { value: 'ar', label: 'Arabic' },
            { value: 'fr', label: 'French' },
        ],
    },
    {
        key: 'is_active',
        label: 'Active',
        type: 'select',
        placeholder: 'Is active',
        required: true,
        options: [
            { value: '0', label: 'Inactive' },
            { value: '1', label: 'Active' },
        ],
    },
    {
        key: 'instagram',
        label: 'Instagram url',
        type: 'text',
        placeholder: 'Enter the Instagram url account',
        required: true,
    },
    {
        key: 'facebook',
        label: 'Facebook',
        type: 'text',
        placeholder: 'Enter the facebook url account',
        required: true,
    },
    { key: 'tiktok', label: 'Tiktok', type: 'text', placeholder: 'Enter the tiktok url account', required: true },
    {
        key: 'description',
        label: 'Description',
        type: 'textarea',
        placeholder: 'Describe the website',
        required: true,
    },
];
</script>

<template>
    <Edit :data="props.data" :columns="columns" table="websites" />
</template>
