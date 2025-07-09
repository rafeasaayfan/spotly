<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'createdBy_name', label: 'Created By' },

    { key: 'name', label: 'Name' },

    { key: 'bg_body_light', label: 'Bg Body Light', type: 'color' },
    { key: 'bg_body_dark', label: 'Bg Body Dark', type: 'color' },
    { key: 'bg_nav_light', label: 'Bg Nav Light', type: 'color' },
    { key: 'bg_nav_dark', label: 'Bg Nav Dark', type: 'color' },
    { key: 'bg_footer_light', label: 'Bg Footer Light', type: 'color' },

    { key: 'bg_field_light', label: 'Bg Field Light', type: 'color' },
    { key: 'bg_field_dark', label: 'Bg Field Dark', type: 'color' },

    { key: 'bg_card_light', label: 'Bg Card Light', type: 'color' },
    { key: 'bg_card_dark', label: 'Bg Card Dark', type: 'color' },

    { key: 'forground_light', label: 'Forground Light', type: 'color' },
    { key: 'forground_dark', label: 'Forground Dark', type: 'color' },
    { key: 'forground_active_light', label: 'Forground Active Light', type: 'color' },
    { key: 'forground_active_dark', label: 'Forground Active Dark', type: 'color' },
    { key: 'forground_muted_light', label: 'Forground Muted Light', type: 'color' },
    { key: 'forground_muted_dark', label: 'Forground Muted Dark', type: 'color' },

    { key: 'primary_light', label: 'Primary Light', type: 'color' },
    { key: 'primary_dark', label: 'Primary Dark', type: 'color' },
    { key: 'danger_light', label: 'Danger Light', type: 'color' },
    { key: 'danger_dark', label: 'Danger Dark', type: 'color' },
    { key: 'secondary_light', label: 'Secondary Light', type: 'color' },
    { key: 'secondary_dark', label: 'Secondary Dark', type: 'color' },

    { key: 'border_color_light', label: 'Border Color Light', type: 'color' },
    { key: 'border_color_dark', label: 'Border Color Dark', type: 'color' },

    { key: 'description', label: 'Description', type: 'textarea' },

    { key: 'is_custom', label: 'Is Custom', type: 'boolean' },
    { key: 'is_active', label: 'Is Active', type: 'active' },

    { key: 'created_at', label: 'Created At', type: 'date' },
    { key: 'updated_at', label: 'Updated At', type: 'date' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
