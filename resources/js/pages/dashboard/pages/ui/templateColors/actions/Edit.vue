<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter the template name', required: true },

    { key: 'bg_body_light', label: 'Bg Body Light', type: 'color', placeholder: 'Enter the body color', required: true },
    { key: 'bg_body_dark', label: 'Bg Body Dark', type: 'color', placeholder: 'Enter the body color', required: true },
    { key: 'bg_nav_light', label: 'Bg Nav Light', type: 'color', placeholder: 'Enter the nav color', required: true },
    { key: 'bg_nav_dark', label: 'Bg Nav Dark', type: 'color', placeholder: 'Enter the nav color', required: true },
    { key: 'bg_footer_light', label: 'Bg Footer Light', type: 'color', placeholder: 'Enter the footer color', required: true },
    { key: 'bg_footer_dark', label: 'Bg Footer Dark', type: 'color', placeholder: 'Enter the footer color', required: true },

    { key: 'forground_light', label: 'Forground Light', type: 'color', placeholder: 'Enter the forground color', required: true },
    { key: 'forground_dark', label: 'Forground Dark', type: 'color', placeholder: 'Enter the forground color', required: true },
    {
        key: 'forground_active_light',
        label: 'Forground Active Light',
        type: 'color',
        placeholder: 'Enter the forground active color',
        required: true,
    },
    { key: 'forground_active_dark', label: 'Forground Active Dark', type: 'color', placeholder: 'Enter the forground active color', required: true },
    { key: 'forground_muted_light', label: 'Forground Muted Light', type: 'color', placeholder: 'Enter the forground muted color', required: true },
    { key: 'forground_muted_dark', label: 'Forground Muted Dark', type: 'color', placeholder: 'Enter the forground muted color', required: true },

    { key: 'bg_field_light', label: 'Bg Field Light', type: 'color', placeholder: 'Enter the field color', required: true },
    { key: 'bg_field_dark', label: 'Bg Field Dark', type: 'color', placeholder: 'Enter the field color', required: true },

    { key: 'bg_card_light', label: 'Bg Card Light', type: 'color', placeholder: 'Enter the card color', required: true },
    { key: 'bg_card_hover_light', label: 'Bg Card Hover Light', type: 'color', placeholder: 'Enter the card hover color', required: true },
    { key: 'bg_card_dark', label: 'Bg Card Dark', type: 'color', placeholder: 'Enter the card color', required: true },
    { key: 'bg_card_hover_dark', label: 'Bg Card Hover Dark', type: 'color', placeholder: 'Enter the card hover color', required: true },

    { key: 'bg_content_light', label: 'Bg Content Light', type: 'color', placeholder: 'Enter the content color', required: true },
    { key: 'bg_content_hover_light', label: 'Bg Content Hover Light', type: 'color', placeholder: 'Enter the content hover color', required: true },
    { key: 'bg_content_active_light', label: 'Bg Content Active Light', type: 'color', placeholder: 'Enter the content active color', required: true },
    { key: 'bg_content_dark', label: 'Bg Content Dark', type: 'color', placeholder: 'Enter the content color', required: true },
    { key: 'bg_content_hover_dark', label: 'Bg Content Hover Dark', type: 'color', placeholder: 'Enter the content hover color', required: true },
    { key: 'bg_content_active_dark', label: 'Bg Content Active Dark', type: 'color', placeholder: 'Enter the content active color', required: true },

    { key: 'bg_dropdown_light', label: 'Bg Dropdown Light', type: 'color', placeholder: 'Enter the dropdown color', required: true },
    { key: 'bg_dropdown_dark', label: 'Bg Dropdown Dark', type: 'color', placeholder: 'Enter the dropdown color', required: true },

    { key: 'primary_light', label: 'Primary Light', type: 'color', placeholder: 'Enter the primary color', required: true },
    { key: 'primary_hover_light', label: 'Primary Hover Light', type: 'color', placeholder: 'Enter the primary hover color', required: true },
    { key: 'primary_dark', label: 'Primary Dark', type: 'color', placeholder: 'Enter the primary color', required: true },
    { key: 'primary_hover_dark', label: 'Primary Hover Dark', type: 'color', placeholder: 'Enter the primary hover color', required: true },
    { key: 'danger_light', label: 'Danger Light', type: 'color', placeholder: 'Enter the danger color', required: true },
    { key: 'danger_hover_light', label: 'Danger Hover Light', type: 'color', placeholder: 'Enter the danger hover color', required: true },
    { key: 'danger_dark', label: 'Danger Dark', type: 'color', placeholder: 'Enter the danger color', required: true },
    { key: 'danger_hover_dark', label: 'Danger Hover Dark', type: 'color', placeholder: 'Enter the danger hover color', required: true },
    { key: 'secondary_light', label: 'Secondary Light', type: 'color', placeholder: 'Enter the secondary color', required: true },
    { key: 'secondary_hover_light', label: 'Secondary Hover Light', type: 'color', placeholder: 'Enter the secondary hover color', required: true },
    { key: 'secondary_dark', label: 'Secondary Dark', type: 'color', placeholder: 'Enter the secondary color', required: true },
    { key: 'secondary_hover_dark', label: 'Secondary Hover Dark', type: 'color', placeholder: 'Enter the secondary hover color', required: true },

    { key: 'border_color_light', label: 'Border Color Light', type: 'color', placeholder: 'Enter the border color', required: true },
    { key: 'border_color_dark', label: 'Border Color Dark', type: 'color', placeholder: 'Enter the border color', required: true },

    {
        key: 'is_active',
        label: 'Is Active',
        type: 'select',
        placeholder: 'Enter the is active',
        required: true,
        options: [
            { label: 'Active', value: 1 },
            { label: 'Inactive', value: 0 },
        ],
    },

    { key: 'description', label: 'Description', type: 'textarea', placeholder: 'Enter the description', required: true, maxlength: 100 },
];
</script>

<template>
    <Edit :data="props.data" :columns="columns" table="templateColors" />
</template>
