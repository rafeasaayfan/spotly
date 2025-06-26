<script setup lang="ts">
import HeadingSmall from '@/components/headers/HeadingSmall.vue';
import PhoneNumberField from '@/components/PhoneNumberField.vue';
import { Input, InputError, Select, SelectWithSearch } from '@/components/ui/fields';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    form: {
        phone_number: string;
        email: string;
        country: string;
        city: string;
        address: string;
        instagram: string;
        facebook: string;
        tiktok: string;
        errors?: Record<string, string>;
    };
    countries: Record<string, any>;
}>();

const emit = defineEmits<{
    (e: 'update', field: string, value: string): void;
}>();

const phone_number = computed({
    get: () => props.form.phone_number,
    set: (val) => emit('update', 'phone_number', val),
});

const email = computed({
    get: () => props.form.email,
    set: (val) => emit('update', 'email', val),
});

const country = computed({
    get: () => props.form.country,
    set: (val) => emit('update', 'country', val),
});

const city = computed({
    get: () => props.form.city,
    set: (val) => emit('update', 'city', val),
});

const address = computed({
    get: () => props.form.address,
    set: (val) => emit('update', 'address', val),
});

const instagram = computed({
    get: () => props.form.instagram,
    set: (val) => emit('update', 'instagram', val),
});

const facebook = computed({
    get: () => props.form.facebook,
    set: (val) => emit('update', 'facebook', val),
});

const tiktok = computed({
    get: () => props.form.tiktok,
    set: (val) => emit('update', 'tiktok', val),
});

const page = usePage<SharedData>();

const mappedCountries = props.countries.map((item: any) => ({
    value: item.country,
    label: page.props.lang === 'ar' ? item.country_ar : page.props.lang === 'en' ? item.country : item.country_fr,
    icon: item.flag,
}));

const mappedCountryPhones = props.countries.map((item: any) => ({
    value: item.phone_code,
    label: item.phone_code,
    icon: item.flag,
}));
</script>

<template>
    <div key="step2" class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="border-muted col-span-3 w-full border-b pb-3">
            <span class="gradient-text text-2xl font-bold">Contact Details</span>
        </div>

        <div class="flex flex-col gap-2">
            <HeadingSmall title="Country" description="The country where you operate." />
            <SelectWithSearch v-model="country" placeholder="Select a country..." :options="mappedCountries" />
            <InputError v-if="props.form.errors?.country" :message="props.form.errors.country" />
        </div>

        <div class="flex flex-col gap-2">
            <HeadingSmall title="Phone Number" description="Your business contact number." />
            <PhoneNumberField v-model="phone_number" :options="mappedCountryPhones" selectedCode="+961" />
            <InputError v-if="props.form.errors?.phone_number" :message="props.form.errors.phone_number" />
        </div>

        <div class="flex flex-col gap-2">
            <HeadingSmall title="Email Address" description="Your public contact email." />
            <Input v-model="email" type="email" placeholder="contact@yourbusiness.com" />
            <InputError v-if="props.form.errors?.email" :message="props.form.errors.email" />
        </div>

        <div class="flex flex-col gap-2 md:col-span-2">
            <HeadingSmall title="Address" description="Your physical street address." />
            <Input v-model="address" type="text" placeholder="123 Main Street" />
            <InputError v-if="props.form.errors?.address" :message="props.form.errors.address" />
        </div>

        <div class="flex flex-col gap-2">
            <HeadingSmall title="City" description="The primary city of your business." />
            <Select v-model="city" placeholder="Select a city..." />
            <InputError v-if="props.form.errors?.city" :message="props.form.errors.city" />
        </div>

        <div class="flex flex-col gap-2">
            <HeadingSmall title="Instagram Url" description="Your public contact email." />
            <Input v-model="instagram" type="url" placeholder="contact@yourbusiness.com" />
            <InputError v-if="props.form.errors?.instagram" :message="props.form.errors.instagram" />
        </div>

        <div class="flex flex-col gap-2">
            <HeadingSmall title="Tiktok Url" description="Your public contact email." />
            <Input v-model="tiktok" type="url" placeholder="contact@yourbusiness.com" />
            <InputError v-if="props.form.errors?.tiktok" :message="props.form.errors.tiktok" />
        </div>

        <div class="flex flex-col gap-2">
            <HeadingSmall title="Facebook Url" description="Your public contact email." />
            <Input v-model="facebook" type="url" placeholder="contact@yourbusiness.com" />
            <InputError v-if="props.form.errors?.facebook" :message="props.form.errors.facebook" />
        </div>
    </div>
</template>
