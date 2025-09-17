<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();

const columns = [
    { key: 'user_name', label: 'User', type: '' },
    { key: 'website_name', label: 'Website', type: '' },
    { key: 'plan_name', label: 'Plan', type: '' },
    { key: 'paymentMethod_name', label: 'Payment Method', type: '' },
    { key: 'amount', label: 'Amount', type: '' },
    { key: 'currency', label: 'Currency', type: '' },
    { key: 'status', label: 'Status', type: 'status' },
    { key: 'transaction_id', label: 'Transaction Id', type: 'details' },
    { key: 'Details', label: 'Details', type: '' },
    { key: 'paid_at', label: 'Paid At', type: 'date' },
    { key: 'created_at', label: 'Created At', type: 'date' },
    { key: 'updated_at', label: 'Updated At', type: 'date' },
];
</script>

<template>
    <View :data="props.data" :columns="columns" />
</template>
