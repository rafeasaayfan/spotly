<script setup lang="ts">
import Create from '@/components/table/actions/Create.vue';

const props = defineProps<{
    websites: Record<string, any>;
    templates: Record<string, any>;
    templateColors: Record<string, any>;
}>();

const mappedWebsites = props.websites.map((item: any) => ({
    value: item.id,
    label: item.name,
}));

const mappedTemplates = props.templates.map((item: any) => ({
    value: item.id,
    label: item.name,
}));

const mappedTemplateColors = props.templateColors.map((item: any) => ({
    value: item.id,
    label: item.name,
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
    {
        key: 'template_id',
        label: 'Template Name',
        type: 'select_with_search',
        placeholder: 'Select a template',
        required: true,
        relation: mappedTemplates,
    },
    {
        key: 'template_color_id',
        label: 'Template Color Name',
        type: 'select_with_search',
        placeholder: 'Select a template color',
        required: true,
        relation: mappedTemplateColors,
    },
    {
        key: 'is_active',
        label: 'Active',
        type: 'select',
        placeholder: 'Select a status',
        required: true,
        options: [
            { value: '0', label: 'Inactive' },
            { value: '1', label: 'Active' },
        ],
    },
];
</script>

<template>
    <Create :columns="columns" table="websiteTemplates" />
</template>
