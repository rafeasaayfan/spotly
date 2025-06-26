<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'flag', label: 'Flag', type: 'image' },
    { key: 'country', label: 'Country' },
    { key: 'country_ar', label: 'Country AR' },
    { key: 'country_fr', label: 'Country FR' },
    { key: 'code', label: 'Code' },
    { key: 'phone_code', label: 'Phone Code' },
    { key: 'is_active', label: 'Active Status', type: 'active' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
