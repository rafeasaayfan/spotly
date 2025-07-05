<script setup lang="ts">
import Create from '@/components/table/actions/Create.vue';

const props = defineProps<{
    websites: Record<string, any>;
}>();

const mappedWebsites = props.websites.map((item : any) => ({
    value: item.id,
    label: item.name,
}));

const columns = [
    { key: 'website_id', label: 'Website', type: 'select_with_search', placeholder: 'Select a website', required: true, relation: mappedWebsites },
    { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter the brand name', required: true },
    { key: 'description', label: 'Description', type: 'textarea', placeholder: 'Describe this brand', required: false, maxlength: 255 },
    {
        key: 'is_active',
        label: 'Is Active',
        type: 'select',
        placeholder: 'Is this a active brand',
        required: true,
        options: [
            { value: '0', label: 'Inactive' },
            { value: '1', label: 'Active' },
        ],
    },
];
</script>

<template>
    <Create :columns="columns" table="brands" />
</template>
