<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';

const props = defineProps<{
    data: Record<string, any>;
    websites: Record<string, any>;
    paymentMethods: Record<string, any>;
}>();

const mappedWebsites = props.websites.map((item: any) => ({
    value: item.id,
    label: item.name,
}));

const mappedPaymentMethods = props.paymentMethods.map((item: any) => ({
    value: item.id,
    label: item.name,
}));

const columns = [
    {
        key: 'website_id',
        label: 'Website Name',
        type: 'select_with_search',
        placeholder: 'Select a website',
        required: true,
        relation: mappedWebsites,
    },
    {
        key: 'payment_method_id',
        label: 'Payment Method',
        type: 'select_with_search',
        placeholder: 'Select a method',
        required: true,
        relation: mappedPaymentMethods,
    },
    {
        key: 'is_active',
        label: 'Active',
        type: 'select',
        placeholder: 'Select status',
        required: true,
        options: [
            { value: '0', label: 'Inactive' },
            { value: '1', label: 'Active' },
        ],
    },
];
</script>

<template>
    <Edit :data="props.data" :columns="columns" table="websitePaymentMethods" />
</template>
