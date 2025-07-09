<script setup lang="ts">
import Create from '@/components/table/actions/Create.vue';

const props = defineProps<{
    templates: Record<string, any>;
    templateColors: Record<string, any>;
}>();

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
        key: 'is_default',
        label: 'Default',
        type: 'select',
        placeholder: 'Is this template color default',
        required: true,
        options: [
            { value: '0', label: 'No' },
            { value: '1', label: 'Yes' },
        ],
    },
];
</script>

<template>
    <Create :columns="columns" table="templateTemplateColors" />
</template>
