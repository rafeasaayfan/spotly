<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'flag', label: 'Flag', type: 'imageFile', placeholder: 'Enter the country flag', required: true },
    { key: 'country', label: 'Country', type: 'text', placeholder: 'Enter the country name in english', required: true },
    { key: 'country_ar', label: 'Country AR', type: 'text', placeholder: 'Enter the country name in arabic', required: true },
    { key: 'country_fr', label: 'Country FR', type: 'text', placeholder: 'Enter the country name in frensh', required: true },
    { key: 'code', label: 'Code', type: 'text', placeholder: 'Enter the country code', required: true },
    { key: 'phone_code', label: 'Phone Code', type: 'text', placeholder: 'Enter the country phone code', required: true },
    {
        key: 'region',
        label: 'Region',
        type: 'select',
        placeholder: 'Enter the country region',
        required: false,
        options: [
            { value: 'africa', label: 'Africa' },
            { value: 'asia', label: 'Asia' },
            { value: 'europe', label: 'Europe' },
            { value: 'north_america', label: 'North America' },
            { value: 'south_america', label: 'South America' },
            { value: 'australia', label: 'Australia' },
            { value: 'antarctica', label: 'Antarctica' },
            { value: 'middle_east', label: 'Middle East' },
            { value: 'oceania', label: 'Oceania' },
            { value: 'other', label: 'Other' },
        ],
    },
    {
        key: 'is_active',
        label: 'Active Status',
        type: 'select',
        placeholder: 'Enter the country active status',
        required: true,
        options: [
            {
                value: '0',
                label: 'Inactive',
            },
            {
                value: '1',
                label: 'Active',
            },
        ],
    },
];
</script>

<template>
    <Edit :data="props.data" :columns="columns" table="countries" />
</template>
