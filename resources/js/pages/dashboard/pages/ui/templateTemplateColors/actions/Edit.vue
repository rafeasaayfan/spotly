<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';

const props = defineProps<{
    data: Record<string, any>;
    websiteTypes: Record<string, any>;
    templates: Record<string, any>;
    templateColors: Record<string, any>;
}>();

const mappedWebsiteTypes = props.websiteTypes.map((item: any) => ({
    value: item.id,
    label: item.type,
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
        key: 'website_type_id',
        label: 'Website Type',
        type: 'select_with_search',
        placeholder: 'Select a type',
        required: true,
        relation: mappedWebsiteTypes,
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
        key: 'uiImages',
        label: 'Template Images',
        type: 'imagesFile',
    },
];
</script>

<template>
    <Edit :data="props.data" :columns="columns" table="templateTemplateColors" />
</template>
