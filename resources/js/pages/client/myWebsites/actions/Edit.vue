<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { File, Input, InputError, PhoneNumberField, Select, SelectWithSearch, Textarea, Toggle } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';

import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { toast } from '@/lib/sweetAlert';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import {
    Building2,
    Facebook,
    Flag,
    Info,
    Instagram,
    Languages,
    LoaderCircle,
    Locate,
    Mail,
    MessageSquareWarning,
    Phone,
    Youtube,
} from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'My Websites',
        href: '/dashboard/my-websites',
    },
    {
        title: 'Edit',
        href: '/dashboard/my-website/Edit',
    },
];

const props = defineProps<{
    website: Record<string, any>;
    countries: Record<string, any>;
    cities: Array<string>;
    flash?: {
        message: string,
    }
}>();

const mappedCountryPhones = props.countries.map((item: any) => ({
    value: item.phone_code,
    label: item.phone_code,
    icon: item.flag,
}));

const page = usePage<SharedData>();
const mappedCountries = props.countries.map((item: any) => ({
    value: item.country,
    label: page.props.lang === 'ar' ? item.country_ar : page.props.lang === 'en' ? item.country : item.country_fr,
    icon: item.flag,
}));

const form = useForm<Record<string, any>>({
    ...props.website,
    light_logo: null,
    dark_logo: null,
});

const submit = () => {
    form.post(route('client.myWebsite.update', props.website.id), {
        onSuccess: () => {
            toast.fire({icon:'success', title: props.flash?.message});
        },
    });
};
</script>

<template>
    <Head title="Website-Edit" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <h1
            class="text-active mx-4 mt-4 mb-2 w-fit rounded-md bg-gradient-to-br from-blue-500/40 via-blue-500/30 to-blue-500/60 px-3 py-2 font-extrabold"
        >
            {{ props.website.name }}
        </h1>

        <form class="border-muted mx-4 mb-4 flex rounded-md border" @submit.prevent="submit">
            <div class="flex flex-col gap-6 rounded-s-md">
                <!-- Logo -->
                <div
                    class="border-muted flex flex-wrap items-center gap-5 border-b p-6"
                    :class="props.website.light_logo || props.website.dark_logo ? 'flex-row' : 'flex-col'"
                >
                    <div class="flex flex-col gap-2">
                        <Label for="light_logo">Light Logo</Label>
                        <File id="light_logo" v-model="form.light_logo" :src="props.website.light_logo" label="Light Logo" />
                        <InputError v-if="form.errors?.light_logo" :message="form.errors.light_logo" />
                    </div>

                    <div class="flex flex-col gap-2">
                        <Label for="dark_logo">Dark Logo</Label>
                        <File id="dark_logo" v-model="form.dark_logo" :src="props.website.dark_logo" label="Dark Logo" />
                        <InputError v-if="form.errors?.dark_logo" :message="form.errors.dark_logo" />
                    </div>
                </div>

                <div class="border-muted flex flex-col gap-2 border-b px-6 pb-6">
                    <Label class="font-bold">Active Your Website</Label>
                    <Toggle
                        :class="props.website.status === 'approved' ? '' : 'cursor-not-allowed'"
                        :btnClass="['w-12 h-6', props.website.status === 'approved' ? '' : 'pointer-events-none']"
                        circleClass="size-4"
                        :modelValue="props.website.is_active === 1"
                    />
                    <div class="mt-2 flex items-center gap-1 text-yellow-700/90">
                        <MessageSquareWarning class="size-4.5" />
                        <span class="text-[11.5px] font-extrabold">Your Website is {{ props.website.status }}, so you cant activate it</span>
                    </div>
                    <!-- @update:modelValue="(val) => updateCol(column.key, val, row.id)" -->
                </div>

                <div class="flex flex-col gap-2 px-6 pb-6">
                    <Button class="opacity-70 hover:opacity-100" variant="destructive">Delete This Website</Button>
                </div>
            </div>

            <div class="border-muted flex flex-1 flex-col gap-6 border-s px-10 py-6">
                <div class="grid grid-cols-2 gap-5 rounded-md">
                    <div class="flex flex-col gap-2">
                        <Label for="phone_number">
                            <Phone class="size-3.5" />
                            Phone Number
                        </Label>
                        <PhoneNumberField id="phone_number" v-model="form.phone_number" :options="mappedCountryPhones" selectedCode="+961" required />
                        <InputError v-if="form.errors?.phone_number" :message="form.errors.phone_number" />
                    </div>

                    <div class="flex flex-col gap-2">
                        <Label for="email">
                            <Mail class="size-3.5" />
                            Email Address
                        </Label>
                        <Input id="email" v-model="form.email" placeholder="Enter your business phone number" required />
                        <InputError v-if="form.errors?.email" :message="form.errors.email" />
                    </div>
                </div>

                <div class="border-muted grid grid-cols-2 gap-5 border-t pt-6">
                    <div class="flex flex-col gap-2">
                        <Label for="country">
                            <Flag class="size-3.5" />
                            Country
                        </Label>
                        <SelectWithSearch v-model="form.country" placeholder="Select a country..." :options="mappedCountries" />
                        <InputError v-if="form.errors?.country" :message="form.errors.country" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <Label for="city">
                            <Building2 class="size-3.5" />
                            City
                        </Label>
                        <SelectWithSearch
                            v-model="form.city"
                            placeholder="Select a city..."
                            :options="cities.map((city) => ({ value: city, label: city }))"
                        />
                        <InputError v-if="form.errors?.city" :message="form.errors.city" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <Label for="address">
                            <Locate class="size-3.5" />
                            Address
                        </Label>
                        <Input id="address" v-model="form.address" placeholder="Enter your business Address" required />
                        <InputError v-if="form.errors?.address" :message="form.errors.address" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <Label for="language">
                            <Languages class="size-3.5" />
                            Default Language
                        </Label>
                        <Select id="language" option="Select a language" v-model="form.language" required>
                            <option
                                v-for="lang in [
                                    { value: 'en', label: 'English' },
                                    { value: 'ar', label: 'Arabic' },
                                    { value: 'fr', label: 'Frensh' },
                                ]"
                                :key="lang.value"
                                :value="lang.value"
                            >
                                {{ lang.label }}
                            </option>
                        </Select>
                        <InputError v-if="form.errors?.language" :message="form.errors.language" />
                    </div>
                </div>

                <div class="border-muted grid grid-cols-2 gap-5 border-t pt-6">
                    <div class="flex flex-col gap-2">
                        <Label for="instagram">
                            <Instagram class="size-3.5" />
                            Instagram
                        </Label>
                        <Input id="instagram" v-model="form.instagram" placeholder="Enter your business Instagram" required />
                        <InputError v-if="form.errors?.instagram" :message="form.errors.instagram" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <Label for="tiktok">
                            <svg viewBox="0 0 256 256" class="size-3.5 stroke-black transition-all duration-300 dark:stroke-white">
                                <path
                                    d="M168,106a95.9,95.9,0,0,0,56,18V84a56,56,0,0,1-56-56H128V156a28,28,0,1,1-40-25.3V89.1A68,68,0,1,0,168,156Z"
                                    fill="none"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="22"
                                />
                            </svg>
                            TikTok
                        </Label>
                        <Input id="tiktok" v-model="form.tiktok" placeholder="Enter your business TikTok" required />
                        <InputError v-if="form.errors?.tiktok" :message="form.errors.tiktok" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <Label for="youtube">
                            <Youtube class="size-3.5" />
                            Youtube
                        </Label>
                        <Input id="youtube" v-model="form.youtube" placeholder="Enter your business Youtube" required />
                        <InputError v-if="form.errors?.youtube" :message="form.errors.youtube" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <Label for="facebook">
                            <Facebook class="size-3.5" />
                            Facebook
                        </Label>
                        <Input id="facebook" v-model="form.facebook" placeholder="Enter your business Facebook" required />
                        <InputError v-if="form.errors?.facebook" :message="form.errors.facebook" />
                    </div>
                </div>

                <div class="border-muted flex w-full flex-col gap-2 border-t pt-6">
                    <Label for="about_us">
                        <Info class="size-3.5" />
                        About Us Section
                    </Label>
                    <Textarea id="about_us" v-model="form.about_us" :maxlength="255" required />
                    <InputError v-if="form.errors?.about_us" :message="form.errors.about_us" />
                </div>
            </div>
        </form>

        <div class="flex w-full justify-end px-4 pb-4">
            <Button @click="submit" size="lg" :disabled="form.processing">
                <LoaderCircle v-if="form.processing" class="size-4.5 animate-spin" />
                Save Change
            </Button>
        </div>
    </DashboardLayout>
</template>
