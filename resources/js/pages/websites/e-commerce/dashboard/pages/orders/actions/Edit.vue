<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

const props = defineProps<{
    data: Record<string, any>;
    cities: Record<string, any>;
}>();

const page = usePage<SharedData>();

const mappedCities = Object.entries(props.cities).map(([key, city]: [string, any]) => ({
    value: key,
    label: page.props.lang === 'ar' ? city.ar : city.en,
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
