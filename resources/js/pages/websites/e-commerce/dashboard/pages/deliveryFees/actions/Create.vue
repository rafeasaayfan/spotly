<script setup lang="ts">
import Create from '@/components/table/actions/Create.vue';

const props = defineProps<{
    cities: Array<string>;
}>();

const mappedCities = props.cities.map((item: any) => ({
    value: item,
    label: item,
}));

const columns = [
    { key: 'city', label: 'City', type: 'select_with_search', placeholder: 'Select a city', required: true, relation: mappedCities },
    { key: 'amount', label: 'Amount', type: 'number', placeholder: 'Enter the delivery amount in $', required: true },
];
</script>

<template>
    <Create :columns="columns" table="deliveryFees" routeDash="website.e-commerce.dashboard.deliveryFees.store" />
</template>
