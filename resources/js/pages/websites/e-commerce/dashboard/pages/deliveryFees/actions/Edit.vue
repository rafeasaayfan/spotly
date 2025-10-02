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
    { key: 'city', label: 'City', type: 'select_with_search', placeholder: 'Select a city', required: true, relation: mappedCities },
    { key: 'amount', label: 'Amount', type: 'number', placeholder: 'Enter the delivery amount in $', required: true },
];
</script>

<template>
    <Edit :data="props.data" :columns="columns" table="deliveryFees" routeDash="website.e-commerce.dashboard.deliveryFees.update" />
</template>
