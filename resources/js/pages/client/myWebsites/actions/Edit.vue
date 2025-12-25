<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogDescription, DialogHeader, DialogScrollContent, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { FileUploader, Input, InputError, PhoneNumberField, Select, SelectWithSearch, Textarea, Toggle } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';

import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { toast } from '@/lib/sweetAlert';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { AlertTriangle, Building2, Facebook, Flag, Info, Instagram, Languages, LoaderCircle, Locate, Mail, Phone, Youtube } from 'lucide-vue-next';
import { watchEffect } from 'vue';
import Delete from './Delete.vue';

const page = usePage<SharedData>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: page.props.lang === 'ar' ? 'مواقعي' : 'My Websites',
        href: '/dashboard/my-websites',
    },
    {
        title: page.props.lang === 'ar' ? 'تعديل' : 'Edit',
        href: '/dashboard/my-website/edit',
    },
];

const props = defineProps<{
    website: Record<string, any>;
    countries: Record<string, any>;
    cities: Record<string, any>;
    flash?: {
        toastType: 'success' | 'error' | 'warning' | 'info';
        message: string;
    };
}>();

watchEffect(() => {
    const message = props.flash?.message;
    if (message) {
        toast.fire({ icon: props.flash?.toastType, title: message });
    }
});

const mappedCountryPhones = props.countries.map((item: any) => ({
    value: item.phone_code,
    label: item.phone_code,
    src: item.flag,
}));

const mappedCountries = props.countries.map((item: any) => ({
    value: item.country,
    label: page.props.lang === 'ar' ? item.country_ar : page.props.lang === 'en' ? item.country : item.country_fr,
    src: item.flag,
}));

const mappedCities = Object.entries(props.cities).map(([key, city]: [string, any]) => ({
    value: key,
    label: page.props.lang === 'ar' ? city.ar : city.en,
}));

const form = useForm<Record<string, any>>({
    ...props.website,
    light_logo: props.website.light_logo,
    dark_logo: props.website.dark_logo,
});

const submit = () => {
    form.post(route('client.myWebsite.update', props.website.id));
};

const activate = (toggleVal: boolean) => {
    form.is_active = toggleVal;

    form.patch(route('client.myWebsite.activate', props.website.id));
};
</script>

<template>
    <Head :title="$t('edit')" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <form
            class="border-muted relative mx-2 my-4 grid grid-cols-1 rounded-md border sm:grid-cols-9 md:mx-4 md:grid-cols-5 lg:grid-cols-6"
            @submit.prevent="submit"
        >
            <div class="flex flex-col rounded-s-md sm:col-span-4 md:col-span-2 lg:col-span-2">
                <div class="border-muted flex flex-col gap-2 border-b p-4 md:p-6">
                    <h1 class="text-active rounded-md bg-gradient-to-br from-blue-500/40 via-blue-500/30 to-blue-500/60 px-4 py-2 font-extrabold">
                        {{ props.website.name }}
                    </h1>
                </div>

                <!-- Logo -->
                <div class="border-muted flex flex-col items-center gap-5 border-b p-4 md:p-6">
                    <div class="flex w-full flex-col gap-1">
                        <Label for="light_logo" class="text-body-muted mb-1 text-xs">{{ $t('myWebsites.light_logo') }}</Label>
                        <FileUploader v-model="form.light_logo" id="light_logo" name="light_logo" />
                        <InputError v-if="form.errors?.light_logo" :message="form.errors.light_logo" class="text-xs" />
                    </div>

                    <div class="flex w-full flex-col gap-1">
                        <Label for="dark_logo" class="text-body-muted mb-1 text-xs">{{ $t('myWebsites.dark_logo') }}</Label>
                        <FileUploader v-model="form.dark_logo" id="dark_logo" name="dark_logo" />
                        <InputError v-if="form.errors?.dark_logo" :message="form.errors.dark_logo" class="text-xs" />
                    </div>
                </div>

                <div class="border-muted flex flex-col gap-2 border-b p-4 md:p-6">
                    <Label class="font-bold">{{ $t('myWebsites.active_your_website') }}</Label>
                    <Toggle
                        :class="form.processing ? 'pointer-events-none opacity-50' : ''"
                        :btnClass="['w-12 h-6', props.website.status === 'approved' ? '' : 'cursor-not-allowed']"
                        circleClass="size-4"
                        :modelValue="props.website.is_active === 1"
                        @update:modelValue="(val) => activate(val)"
                    />
                    <div v-if="props.website.status !== 'approved'" class="mt-2 flex items-center gap-1 text-yellow-700/90">
                        <AlertTriangle class="size-4" />
                        <span class="text-[11.5px] font-extrabold"
                            >{{ $t('myWebsites.your_website_is') }} {{ props.website.status }}, {{ $t('myWebsites.so_you_cant_activate_it') }}</span
                        >
                    </div>
                </div>

                <div class="hidden flex-col gap-2 p-4 p-6 sm:flex md:p-6">
                    <Dialog>
                        <DialogTrigger as-child>
                            <Button size="sm" class="opacity-70 hover:opacity-100" variant="destructive">{{
                                $t('myWebsites.delete_this_website')
                            }}</Button>
                        </DialogTrigger>

                        <DialogScrollContent>
                            <DialogHeader>
                                <DialogTitle>{{ $t('delete') }} {{ props.website.name }}</DialogTitle>
                                <DialogDescription class="sr-only"> No description provided. </DialogDescription>
                            </DialogHeader>

                            <Delete :websiteId="props.website.id" />
                        </DialogScrollContent>
                    </Dialog>
                </div>
            </div>

            <div class="border-muted flex flex-col gap-6 border-s px-4 py-6 sm:col-span-5 md:col-span-3 md:px-10 lg:col-span-4">
                <div class="grid grid-cols-1 gap-5 rounded-md lg:grid-cols-2">
                    <div class="flex flex-col gap-1">
                        <Label for="phone_number" class="text-body-muted mb-1 text-xs">
                            <Phone class="size-3.5" />
                            {{ $t('phone') }}
                        </Label>
                        <PhoneNumberField id="phone_number" v-model="form.phone_number" :options="mappedCountryPhones" selectedCode="+961" required />
                        <InputError v-if="form.errors?.phone_number" :message="form.errors.phone_number" />
                    </div>

                    <div class="flex flex-col gap-1">
                        <Label for="email" class="text-body-muted mb-1 text-xs">
                            <Mail class="size-3.5" />
                            {{ $t('email') }}
                        </Label>
                        <Input type="email" id="email" v-model="form.email" placeholder="contact@yourbusiness.com" required />
                        <InputError v-if="form.errors?.email" :message="form.errors.email" />
                    </div>
                </div>

                <div class="border-muted grid grid-cols-1 gap-5 border-t pt-6 lg:grid-cols-2">
                    <div class="flex flex-col gap-1">
                        <Label for="country" class="text-body-muted mb-1 text-xs">
                            <Flag class="size-3.5" />
                            {{ $t('country') }}
                        </Label>
                        <SelectWithSearch v-model="form.country" placeholder="Select a country..." :options="mappedCountries" />
                        <InputError v-if="form.errors?.country" :message="form.errors.country" />
                    </div>
                    <div class="flex flex-col gap-1">
                        <Label for="city" class="text-body-muted mb-1 text-xs">
                            <Building2 class="size-3.5" />
                            {{ $t('city') }}
                        </Label>
                        <SelectWithSearch v-model="form.city" placeholder="Select a city..." :options="mappedCities" />
                        <InputError v-if="form.errors?.city" :message="form.errors.city" />
                    </div>
                    <div class="flex flex-col gap-1">
                        <Label for="address" class="text-body-muted mb-1 text-xs">
                            <Locate class="size-3.5" />
                            {{ $t('address') }}
                        </Label>
                        <Input id="address" v-model="form.address" :placeholder="$t('street_address_placeholder')" required />
                        <InputError v-if="form.errors?.address" :message="form.errors.address" />
                    </div>
                    <div class="flex flex-col gap-1">
                        <Label for="language" class="text-body-muted mb-1 text-xs">
                            <Languages class="size-3.5" />
                            {{ $t('default_language') }}
                        </Label>
                        <Select id="language" option="Select a language" v-model="form.language" required>
                            <option
                                v-for="lang in [
                                    { value: 'en', label: page.props.lang === 'ar' ? 'الإنجليزية' : 'English' },
                                    { value: 'ar', label: page.props.lang === 'ar' ? 'العربية' : 'Arabic' },
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

                <div class="border-muted grid grid-cols-1 gap-5 border-t pt-6 lg:grid-cols-2">
                    <div class="flex flex-col gap-1">
                        <Label for="instagram" class="text-body-muted mb-1 text-xs">
                            <Instagram class="size-3.5" />
                            {{ $t('instagram') }}
                        </Label>
                        <Input type="url" id="instagram" v-model="form.instagram" placeholder="https://instagram.com/yourbusiness" required />
                        <InputError v-if="form.errors?.instagram" :message="form.errors.instagram" />
                    </div>
                    <div class="flex flex-col gap-1">
                        <Label
                            for="tiktok"
                            class="text-body-muted mb-1 stroke-black/50 text-xs hover:stroke-black dark:stroke-white/50 hover:dark:stroke-white"
                        >
                            <svg viewBox="0 0 256 256" class="size-3.5 transition-all duration-300">
                                <path
                                    d="M168,106a95.9,95.9,0,0,0,56,18V84a56,56,0,0,1-56-56H128V156a28,28,0,1,1-40-25.3V89.1A68,68,0,1,0,168,156Z"
                                    fill="none"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="22"
                                />
                            </svg>
                            {{ $t('tiktok') }}
                        </Label>
                        <Input type="url" id="tiktok" v-model="form.tiktok" placeholder="https://tiktok.com/yourbusiness" required />
                        <InputError v-if="form.errors?.tiktok" :message="form.errors.tiktok" />
                    </div>
                    <div class="flex flex-col gap-1">
                        <Label for="youtube" class="text-body-muted mb-1 text-xs">
                            <Youtube class="size-3.5" />
                            {{ $t('youtube') }}
                        </Label>
                        <Input type="url" id="youtube" v-model="form.youtube" placeholder="https://youtube.com/yourbusiness" required />
                        <InputError v-if="form.errors?.youtube" :message="form.errors.youtube" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <Label for="facebook">
                            <Facebook class="size-3.5" />
                            {{ $t('facebook') }}
                        </Label>
                        <Input type="url" id="facebook" v-model="form.facebook" placeholder="https://facebook.com/yourbusiness" required />
                        <InputError v-if="form.errors?.facebook" :message="form.errors.facebook" />
                    </div>
                </div>

                <div class="border-muted flex w-full flex-col gap-5 border-t pt-6">
                    <div class="flex flex-col">
                        <Label for="about_us" class="mb-2">
                            <Info class="size-3.5" />
                            {{ $t('about_us_section') }}
                        </Label>
                        <Textarea
                            id="about_us"
                            v-model="form.about_us"
                            :maxlength="255"
                            :placeholder="$t('websiteBuilder.firstStep.about_us_placeholder')"
                            required
                        />
                        <InputError v-if="form.errors?.about_us" :message="form.errors.about_us" />
                    </div>

                    <div class="flex flex-col">
                        <Label for="about_us_ar" class="mb-2">
                            <Info class="size-3.5" />
                            {{ $t('about_us_section_ar') }}
                        </Label>
                        <Textarea
                            id="about_us_ar"
                            v-model="form.about_us_ar"
                            :placeholder="$t('websiteBuilder.firstStep.about_us_placeholder_ar')"
                            :maxlength="255"
                            required
                        />
                        <InputError v-if="form.errors?.about_us_ar" :message="form.errors.about_us_ar" />
                    </div>
                </div>
            </div>
        </form>

        <div class="flex w-full items-center justify-end gap-2 px-2 pb-4 md:px-4">
            <div class="block sm:hidden">
                <Dialog>
                    <DialogTrigger as-child>
                        <Button size="sm" class="opacity-70 hover:opacity-100" variant="destructive">{{
                            $t('myWebsites.delete_this_website')
                        }}</Button>
                    </DialogTrigger>

                    <DialogScrollContent>
                        <DialogHeader>
                            <DialogTitle>Delete {{ props.website.name }}</DialogTitle>
                            <DialogDescription class="sr-only"> No description provided. </DialogDescription>
                        </DialogHeader>

                        <Delete :websiteId="props.website.id" />
                    </DialogScrollContent>
                </Dialog>
            </div>
            <Button @click="submit" :disabled="form.processing">
                <LoaderCircle v-if="form.processing" class="size-4.5 animate-spin" />
                {{ $t('save_change') }}
            </Button>
        </div>
    </DashboardLayout>
</template>
