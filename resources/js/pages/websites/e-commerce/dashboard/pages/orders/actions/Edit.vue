<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';

const props = defineProps<{
    data: Record<string, any>;
    cities: Array<string>;
}>();

const mappedCities = props.cities.map((item: any) => ({
    value: item,
    label: item,
}));

const columns = [
    {
        key: 'delivery_address',
        label: 'Delivery Address',
        type: 'text',
        placeholder: 'Enter the delivery address',
        required: true,
    },
    {
        key: 'city',
        label: 'City',
        type: 'select_with_search',
        placeholder: 'Select a city',
        required: true,
        relation: mappedCities,
    },
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        placeholder: 'Select a status',
        required: true,
        options: [
            { value: 'pending', label: 'Pending' },
            { value: 'confirmed', label: 'Confirmed' },
            { value: 'delivered', label: 'Delivered' },
            { value: 'cancelled', label: 'Cancelled' },
            { value: 'refunded', label: 'Refunded' },
        ],
    },
    { key: 'note', label: 'Note', type: 'textarea', placeholder: 'Your note', required: true, maxlength: 255 },
    {
        key: 'cancellation_reason',
        label: 'Cancellation Reason',
        type: 'textarea',
        placeholder: 'Cancellation or Refunded reason',
        required: true,
        maxlength: 255
    }
];
</script>

<template>
    <Edit :data="props.data" :columns="columns" table="orders" routeDash="website.e-commerce.dashboard.orders.update" />
</template>
