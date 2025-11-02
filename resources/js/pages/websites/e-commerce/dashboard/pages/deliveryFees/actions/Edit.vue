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
    { key: 'city', label: 'City', type: 'select_with_search', placeholder: 'Select a city', required: true, relation: mappedCities },
    { key: 'amount', label: 'Amount', type: 'number', placeholder: 'Enter the delivery amount in $', required: true },
];
</script>

<template>
    <Edit :data="props.data" :columns="columns" table="deliveryFees" routeDash="website.e-commerce.dashboard.deliveryFees.update" />
</template>
