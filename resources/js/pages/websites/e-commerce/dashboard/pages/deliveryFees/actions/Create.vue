<script setup lang="ts">
import Create from '@/components/table/actions/Create.vue';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

const props = defineProps<{
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
    <Create :columns="columns" table="deliveryFees" routeDash="website.e-commerce.dashboard.deliveryFees.store" />
</template>
