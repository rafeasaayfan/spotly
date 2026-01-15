<script setup lang="ts">
import Edit from '@/components/table/actions/Edit.vue';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

const props = defineProps<{
    data: Record<string, any>;
    cities: Record<string, any>;
    countries: Record<string, any>;
}>();

const page = usePage<SharedData>();

const mappedCities = Object.entries(props.cities).map(([key, city]: [string, any]) => ({
    value: key,
    label: page.props.lang === 'ar' ? city.ar : city.en,
}));

const mappedCountries = props.countries.map((item: Record<string, any>) => ({
    value: item.phone_code,
    label: item.phone_code,
    src: item.flag,
}));

const handleColsByStatus = (): any[] => {
    switch (props.data.status) {
        case 'pending':
            return [
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
                    key: 'phone_number',
                    label: 'Phone Number',
                    type: 'phone_number',
                    placeholder: 'Enter the phone number',
                    required: true,
                    options: mappedCountries,
                },
                { key: 'note', label: 'Note', type: 'textarea', placeholder: 'Your note', required: true, maxlength: 255 },
                {
                    key: 'status_reason',
                    label: 'Status Reason',
                    type: 'textarea',
                    placeholder: 'Why you changed the order status?',
                    required: true,
                    maxlength: 255,
                },
            ];
        default:
            return [
                {
                    key: 'status_reason',
                    label: 'Status Reason',
                    type: 'textarea',
                    placeholder: 'Why you changed the order status?',
                    required: true,
                    maxlength: 255,
                },
            ];
    }
};

const columns = handleColsByStatus();
</script>

<template>
    <Edit :data="props.data" :columns="columns" table="orders" routeDash="website.e-commerce.dashboard.orders.update" />
</template>
