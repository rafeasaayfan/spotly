<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

console.log(props.data);


const columns = [
    { key: 'logo', label: 'Website logo', type: 'image' },
    { key: 'owner_name', label: 'Website owner' },
    { key: 'websiteType_type', label: 'Website type' },
    { key: 'viewedBy_name', label: 'Viewed by' },
    { key: 'name', label: 'Name' },
    { key: 'subdomain', label: 'Sub domain' },
    { key: 'country', label: 'Country' },
    { key: 'city', label: 'City' },
    { key: 'address', label: 'Address' },
    { key: 'phone_number', label: 'Phone number', type: 'phone_number' },
    { key: 'instagram', label: 'Instagram url', type: 'url' },
    { key: 'facebook', label: 'Facebook', type: 'url' },
    { key: 'tiktok', label: 'Tiktok url', type: 'url' },
    { key: 'language', label: 'Default language' },
    { key: 'views_count', label: 'Website views' },
    { key: 'is_active', label: 'Active', type: 'active' },
    { key: 'status', label: 'Status', type: 'status' },
    { key: 'description', label: 'Description', type: 'textarea' },
    { key: 'created_at', label: 'Created at', type: 'date' },
    { key: 'updated_at', label: 'Updated at', type: 'date' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
