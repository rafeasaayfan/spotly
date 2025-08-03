<script setup lang="ts">
import HeadingSmall from '@/components/headers/HeadingSmall.vue';
import { Input, InputError, SelectWithSearch, PhoneNumberField } from '@/components/ui/fields';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { Phone } from 'lucide-vue-next';
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
        youtube: string;
        errors?: Record<string, string>;
    };
    countries: Record<string, any>;
    cities: Array<string>;
}>();

// Define emit for updating form fields
const emit = defineEmits<{
    (e: 'update', field: string, value: string): void;
}>();

// Computed properties for two-way binding with form fields
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

const youtube = computed({
    get: () => props.form.youtube,
    set: (val) => emit('update', 'youtube', val),
});

const page = usePage<SharedData>();

// Map countries for select options
const mappedCountries = props.countries.map((item: any) => ({
    value: item.country,
    label: page.props.lang === 'ar' ? item.country_ar : page.props.lang === 'en' ? item.country : item.country_fr,
    icon: item.flag,
}));

// Map country phone codes for phone number field
const mappedCountryPhones = props.countries.map((item: any) => ({
    value: item.phone_code,
    label: item.phone_code,
    icon: item.flag,
}));
</script>

<template>
    <div key="step2" class="grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-3 md:gap-y-10">
        <!-- Section Title -->
        <div class="border-muted col-span-1 w-full border-b pb-3 md:col-span-3">
            <h1 class="flex items-center gap-2 text-xl font-bold sm:text-2xl">
                <Phone class="text-active-link size-5 sm:size-6" />
                <span class="gradient-text">Contact Information</span>
            </h1>
        </div>

        <!-- Phone and Email -->
        <div class="grid grid-cols-1 gap-6 md:col-span-3 md:grid-cols-2">
            <div class="col-span-1 flex flex-col gap-2">
                <HeadingSmall title="Phone Numbe*" description="Enter your business contact number." />
                <div class="flex flex-col gap-1 ps-2">
                    <PhoneNumberField v-model="phone_number" :options="mappedCountryPhones" selectedCode="+961" />
                    <InputError v-if="props.form.errors?.phone_number" :message="props.form.errors.phone_number" />
                </div>
            </div>

            <div class="col-span-1 flex flex-col gap-2">
                <HeadingSmall title="Email Address" description="Enter your business contact email address." />
                <div class="flex flex-col gap-1 ps-2">
                    <Input v-model="email" type="email" placeholder="contact@yourbusiness.com" />
                    <InputError v-if="props.form.errors?.email" :message="props.form.errors.email" />
                </div>
            </div>
        </div>

        <!-- Address, Country, City -->
        <div class="col-span-1 flex flex-col gap-2 md:col-span-1">
            <HeadingSmall title="Street Address" description="Enter your business's physical address." />
            <div class="flex flex-col gap-1 ps-2">
                <Input v-model="address" type="text" placeholder="123 Main Street" />
                <InputError v-if="props.form.errors?.address" :message="props.form.errors.address" />
            </div>
        </div>

        <div class="col-span-1 flex flex-col gap-2 md:col-span-1">
            <HeadingSmall title="Country" description="Select the business's country." />
            <div class="flex flex-col gap-1 ps-2">
                <SelectWithSearch v-model="country" placeholder="Select a country..." :options="mappedCountries" />
                <InputError v-if="props.form.errors?.country" :message="props.form.errors.country" />
            </div>
        </div>

        <div class="col-span-1 flex flex-col gap-2 md:col-span-1">
            <HeadingSmall title="City" description="Select the business's city." />
            <div class="flex flex-col gap-1 ps-2">
                <SelectWithSearch v-model="city" placeholder="Select a city..." :options="cities.map((city) => ({ value: city, label: city }))" />
                <InputError v-if="props.form.errors?.city" :message="props.form.errors.city" />
            </div>
        </div>

        <!-- Social Media Links -->
        <div class="grid grid-cols-1 gap-6 md:col-span-3 md:grid-cols-2 lg:grid-cols-4">
            <div class="col-span-1 flex flex-col gap-2">
                <HeadingSmall title="Instagram URL" description="Link to your Instagram profile." />
                <div class="flex flex-col gap-2 ps-2">
                    <Input v-model="instagram" type="url" placeholder="https://instagram.com/yourbusiness" />
                    <InputError v-if="props.form.errors?.instagram" :message="props.form.errors.instagram" />
                </div>
            </div>

            <div class="col-span-1 flex flex-col gap-2">
                <HeadingSmall title="Facebook URL" description="Link to your Facebook page." />
                <div class="flex flex-col gap-1 ps-2">
                    <Input v-model="facebook" type="url" placeholder="https://facebook.com/yourbusiness" />
                    <InputError v-if="props.form.errors?.facebook" :message="props.form.errors.facebook" />
                </div>
            </div>

            <div class="col-span-1 flex flex-col gap-2">
                <HeadingSmall title="TikTok URL" description="Link to your TikTok profile." />
                <div class="flex flex-col gap-1 ps-2">
                    <Input v-model="tiktok" type="url" placeholder="https://tiktok.com/@yourbusiness" />
                    <InputError v-if="props.form.errors?.tiktok" :message="props.form.errors.tiktok" />
                </div>
            </div>

            <div class="col-span-1 flex flex-col gap-2">
                <HeadingSmall title="YouTube URL" description="Link to your YouTube channel." />
                <div class="flex flex-col gap-1 ps-2">
                    <Input v-model="youtube" type="url" placeholder="https://youtube.com/yourbusiness" />
                    <InputError v-if="props.form.errors?.youtube" :message="props.form.errors.youtube" />
                </div>
            </div>
        </div>
    </div>
</template>
