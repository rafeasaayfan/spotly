<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

const props = defineProps<{
    data: Record<string, any>;
    users: Record<string, any>;
    websiteTypes: Record<string, any>;
    cities: Record<string, any>;
    countries: Record<string, any>;
}>();

const mappedUsers = props.users.map((item : any) => ({
    value: item.id,
    label: item.name,
}));

const mappedTypes = props.websiteTypes.map((item: any) => ({
    value: item.id,
    label: item.type,
}));

const mappedCountries = props.countries.map((item: any) => ({
    value: item.phone_code,
    label: item.phone_code,
    icon: item.flag,
}));

const page = usePage<SharedData>();

const mappedCities = Object.entries(props.cities).map(([key, city]: [string, any]) => ({
    value: key,
    label: page.props.lang === 'ar' ? city.ar : city.en,
}));

const columns = [
    { key: 'owner_id', label: 'Website owner', type: 'select_with_search', placeholder: 'Select the owner', required: true, relation: mappedUsers },
    {
        key: 'website_type_id',
        label: 'Website type',
        type: 'select_with_search',
        placeholder: 'Enter the type',
        required: true,
        relation: mappedTypes,
    },
    { key: 'name', label: 'Website name', type: 'text', placeholder: 'Enter the website name', required: true },
    { key: 'subdomain', label: 'Sub domain', type: 'text', placeholder: 'Enter the sub domain', required: true },
    {
        key: 'phone_number',
        label: 'Phone Number',
        type: 'phone_number',
        placeholder: 'Enter the website phone number',
        required: true,
        options: mappedCountries,
    },
    { key: 'email', label: 'Email', type: 'text', placeholder: 'Enter the website email', required: false },
    { key: 'about_us', label: 'About Us', type: 'textarea', placeholder: 'Enter the website about us', required: false, maxlength: 255 },
    { key: 'light_logo', label: 'Website light logo', type: 'file', placeholder: 'Enter the website light logo', required: false },
    { key: 'dark_logo', label: 'Website dark logo', type: 'file', placeholder: 'Enter the website dark logo', required: false },
    {
        key: 'city',
        label: 'City',
        type: 'select_with_search',
        placeholder: 'Select the city',
        required: false,
        relation: mappedCities,
    },
    { key: 'address', label: 'Address', type: 'text', placeholder: 'Enter the address', required: true },
    { key: 'instagram', label: 'Instagram', type: 'text', placeholder: 'Enter the instagram url', required: false },
    { key: 'facebook', label: 'Facebook', type: 'text', placeholder: 'Enter the facebook url', required: false },
    { key: 'tiktok', label: 'Tiktok', type: 'text', placeholder: 'Enter the tiktok url', required: false },
    { key: 'youtube', label: 'Youtube', type: 'text', placeholder: 'Enter the youtube url', required: false },
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
        key: 'is_verified',
        label: 'Verified',
        type: 'select',
        placeholder: 'Is verified',
        required: true,
        options: [
            { value: '0', label: 'Unverified' },
            { value: '1', label: 'Verified' },
        ],
    },
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        placeholder: 'Select the website status',
        required: true,
        options: [
            { value: 'pending', label: 'Pending' },
            { value: 'denied', label: 'Denied' },
            { value: 'approved', label: 'Approved' },
        ],
    },
];
</script>

<template>
    <Edit :data="props.data" :columns="columns" table="websites" />
</template>
