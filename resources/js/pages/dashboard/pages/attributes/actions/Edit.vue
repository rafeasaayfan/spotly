<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';

const props = defineProps<{
    data: Record<string, any>;
    attributes: Record<string, any>;
    websites: Record<string, any>;
}>();

const mappedAttributes = props.attributes.map((item: Record<string, any>) => ({
    value: item.value,
    label: item.label,
}));

const mappedWebsites = props.websites.map((item: Record<string, any>) => ({
    value: item.id,
    label: item.name,
}));

const columns = [
    {
        key: 'website_id',
        label: 'Website*',
        type: 'select_with_search',
        placeholder: 'Select the website',
        required: true,
        relation: mappedWebsites,
    },
    {
        key: 'name',
        label: 'Name*',
        type: 'select',
        placeholder: 'Enter the attribute name (e.g., Color, Size)',
        required: true,
        options: mappedAttributes,
    },
    {
        key: 'values',
        label: 'Values (Optional — leave empty to allow custom product values)',
        type: 'multiInput',
        placeholder: 'Enter the attribute values (e.g., Red, XL)',
        required: false,
    },
    {
        key: 'is_required',
        label: 'Is Required*',
        type: 'select',
        placeholder: 'Should this attribute be required?',
        required: true,
        options: [
            { value: '0', label: 'No, Optional' },
            { value: '1', label: 'Yes, Required' },
        ],
    },

    {
        key: 'is_active',
        label: 'Is Active*',
        type: 'select',
        placeholder: 'Is this attribute active?',
        required: true,
        options: [
            { value: '0', label: 'Inactive' },
            { value: '1', label: 'Active' },
        ],
    },

    {
        key: 'description',
        label: 'Description',
        type: 'textarea',
        placeholder: 'Describe what this attribute is used for (optional)',
        required: false,
    },
];
</script>

<template>
    <Edit :data="props.data" :columns="columns" table="attributes" />
</template>
