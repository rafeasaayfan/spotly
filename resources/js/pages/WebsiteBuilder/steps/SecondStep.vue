<script setup lang="ts">
import HeadingSmall from '@/components/headers/HeadingSmall.vue';
import { Input, InputError, PhoneNumberField, SelectWithSearch } from '@/components/ui/fields';
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
    <!-- Phone and Email -->
    <div class="grid grid-cols-1 gap-4 md:col-span-3 lg:grid-cols-2">
        <div class="col-span-1 flex flex-col gap-2">
            <HeadingSmall
                :title="$t('websiteBuilder.secondStep.phone_number_title')"
                titleClass="text-body"
                :description="$t('websiteBuilder.secondStep.phone_number_description')"
            />
            <div class="flex flex-col gap-1 ps-2">
                <PhoneNumberField
                    v-model="phone_number"
                    :options="mappedCountryPhones"
                    selectedCode="+961"
                    selectParentClass="sm:col-span-1 md:col-span-2"
                    inputClass="sm:col-span-6 md:col-span-5 h-10"
                    selectClass="h-10"
                />
                <InputError v-if="props.form.errors?.phone_number" :message="props.form.errors.phone_number" />
            </div>
        </div>

        <div class="col-span-1 flex flex-col gap-2">
            <HeadingSmall
                :title="$t('websiteBuilder.secondStep.email_address_title')"
                titleClass="text-body"
                :description="$t('websiteBuilder.secondStep.email_address_description')"
            />
            <div class="flex flex-col gap-1 ps-2">
                <Input
                    v-model="email"
                    type="email"
                    placeholder="contact@yourbusiness.com"
                    class="h-10"
                />
                <InputError v-if="props.form.errors?.email" :message="props.form.errors.email" />
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:col-span-3 lg:grid-cols-3">
        <!-- Address, Country, City -->
        <div class="col-span-1 flex flex-col gap-2">
            <HeadingSmall :title="$t('websiteBuilder.secondStep.street_address_title')" titleClass="text-body" />
            <div class="flex flex-col gap-1 ps-2">
                <Input
                    v-model="address"
                    type="text"
                    :placeholder="$t('street_address_placeholder')"
                    class="h-10"
                />
                <InputError v-if="props.form.errors?.address" :message="props.form.errors.address" />
            </div>
        </div>

        <div class="col-span-1 flex flex-col gap-2">
            <HeadingSmall :title="$t('websiteBuilder.secondStep.country_title')" titleClass="text-body" />
            <div class="flex flex-col gap-1 ps-2">
                <SelectWithSearch
                    v-model="country"
                    :placeholder="$t('websiteBuilder.secondStep.country_placeholder')"
                    :options="mappedCountries"
                    class="h-10"
                />
                <InputError v-if="props.form.errors?.country" :message="props.form.errors.country" />
            </div>
        </div>

        <div class="col-span-1 flex flex-col gap-2">
            <HeadingSmall :title="$t('websiteBuilder.secondStep.city_title')" titleClass="text-body" />
            <div class="flex flex-col gap-1 ps-2">
                <SelectWithSearch
                    v-model="city"
                    :placeholder="$t('websiteBuilder.secondStep.city_placeholder')"
                    :options="cities.map((city) => ({ value: city, label: city }))"
                    class="h-10"
                />
                <InputError v-if="props.form.errors?.city" :message="props.form.errors.city" />
            </div>
        </div>
    </div>

    <!-- Social Media Links -->
    <div class="grid grid-cols-1 gap-4 md:col-span-3 lg:grid-cols-2">
        <div class="col-span-1 flex flex-col gap-2">
            <HeadingSmall :title="$t('websiteBuilder.secondStep.instagram_url_title')" titleClass="text-body" />
            <div class="flex flex-col gap-2 ps-2">
                <Input
                    v-model="instagram"
                    type="url"
                    placeholder="https://instagram.com/yourbusiness"
                    class="h-10"
                />
                <InputError v-if="props.form.errors?.instagram" :message="props.form.errors.instagram" />
            </div>
        </div>

        <div class="col-span-1 flex flex-col gap-2">
            <HeadingSmall :title="$t('websiteBuilder.secondStep.facebook_url_title')" titleClass="text-body" />
            <div class="flex flex-col gap-1 ps-2">
                <Input
                    v-model="facebook"
                    type="url"
                    placeholder="https://facebook.com/yourbusiness"
                    class="h-10"
                />
                <InputError v-if="props.form.errors?.facebook" :message="props.form.errors.facebook" />
            </div>
        </div>

        <div class="col-span-1 flex flex-col gap-2">
            <HeadingSmall :title="$t('websiteBuilder.secondStep.tiktok_url_title')" titleClass="text-body" />
            <div class="flex flex-col gap-1 ps-2">
                <Input
                    v-model="tiktok"
                    type="url"
                    placeholder="https://tiktok.com/yourbusiness"
                    class="h-10"
                />
                <InputError v-if="props.form.errors?.tiktok" :message="props.form.errors.tiktok" />
            </div>
        </div>

        <div class="col-span-1 flex flex-col gap-2">
            <HeadingSmall :title="$t('websiteBuilder.secondStep.youtube_url_title')" titleClass="text-body" />
            <div class="flex flex-col gap-1 ps-2">
                <Input
                    v-model="youtube"
                    type="url"
                    placeholder="https://youtube.com/yourbusiness"
                    class="h-10"
                />
                <InputError v-if="props.form.errors?.youtube" :message="props.form.errors.youtube" />
            </div>
        </div>
    </div>
</template>
