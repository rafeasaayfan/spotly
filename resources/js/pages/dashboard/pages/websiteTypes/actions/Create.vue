<script setup lang="ts">
import Create from '@/components/table/actions/Create.vue';

const props = defineProps<{}>();

//const mappedOrders = props.orders.map((order) => ({
//    value: order.id,
//   label: order.delivery_address,
//}));

const columns = [
    { key: 'type', label: 'Type', type: 'text', required: true },
    {
        key: 'is_active',
        label: 'Active',
        type: 'select',
        required: true,
        options: [
            { value: '0', label: 'No' },
            { value: '1', label: 'Yes' },
        ],
    },
];
</script>

<template>
    <Create :columns="columns" table="websiteTypes" href="websiteTypes" />
</template>
