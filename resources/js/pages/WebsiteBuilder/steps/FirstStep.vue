<script setup lang="ts">
import HeadingSmall from '@/components/headers/HeadingSmall.vue';
import { Input, InputError, Select, SelectWithSearch, Textarea } from '@/components/ui/fields';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    form: {
        website_type_id: number;
        name: string;
        subdomain: string;
        about_us: string;
        about_us_ar: string;
        language: string;
        errors?: Record<string, string>;
    };
    type: string;
    websiteTypes: Record<string, any>;
}>();

// Define emit for updating form fields
const emit = defineEmits<{
    (e: 'update', field: string, value: string | number): void;
}>();

// Map website types for select options
const mappedTypes = props.websiteTypes.map((item: any) => ({
    value: item.id,
    label: item.type,
}));

// Computed properties for two-way binding with form fields
const type = computed({
    get: () => props.type,
    set: (val) => {
        emit('update', 'type', val);
    },
});

const website_type_id = computed({
    get: () => props.form.website_type_id,
    set: (val) => {
        emit('update', 'website_type_id', val);

        const newType = computed(() => mappedTypes?.find((option: { value: string | number }) => option.value === val)?.label || '');

        type.value = newType.value;
    },
});

const name = computed({
    get: () => props.form.name,
    set: (val) => emit('update', 'name', val),
});

const subdomain = computed({
    get: () => props.form.subdomain,
    set: (val) => emit('update', 'subdomain', val),
});

const about_us = computed({
    get: () => props.form.about_us,
    set: (val) => emit('update', 'about_us', val),
});

const about_us_ar = computed({
    get: () => props.form.about_us_ar,
    set: (val) => emit('update', 'about_us_ar', val),
});

const language = computed({
    get: () => props.form.language,
    set: (val) => emit('update', 'language', val),
});

const page = usePage<SharedData>();

const languages = () => {
    if (page.props.lang === 'ar') {
        return [
            { value: 'en', label: 'الإنجليزية' },
            { value: 'ar', label: 'العربية' },
        ];
    }

    return [
        { value: 'en', label: 'English' },
        { value: 'ar', label: 'Arabic' },
    ];
};
</script>

<template>
    <div class="grid grid-cols-1 gap-4 md:col-span-3 md:grid-cols-2">
        <!-- Website Type Selection -->
        <div class="col-span-1 flex flex-col gap-2">
            <HeadingSmall
                :title="$t('websiteBuilder.firstStep.website_type_title')"
                titleClass="text-body"
                :description="$t('websiteBuilder.firstStep.website_type_description')"
            />
            <div class="flex flex-col gap-1 ps-2">
                <SelectWithSearch
                    v-model="website_type_id"
                    :placeholder="$t('websiteBuilder.firstStep.website_type_placeholder')"
                    dropdownItemClass="bg-landing-content-2"
                    searchClass="bg-[var(--field-landing)]"
                    :options="mappedTypes"
                    class="h-10"
                    :withReset="false"
                />
                <InputError v-if="props.form.errors?.website_type_id" :message="props.form.errors.website_type_id" />
            </div>
        </div>

        <!-- Language Selection -->
        <div class="col-span-1 flex flex-col gap-2 md:col-span-1">
            <HeadingSmall
                :title="$t('websiteBuilder.firstStep.primary_language_title')"
                titleClass="text-body"
                :description="$t('websiteBuilder.firstStep.primary_language_description')"
            />
            <div class="flex flex-col gap-1 ps-2">
                <Select
                    v-model="language"
                    :placeholder="$t('websiteBuilder.firstStep.primary_language_placeholder')"
                    dropdownItemClass="bg-landing-content-2"
                    class="h-10"
                    :withReset="false"
                >
                    <option v-for="option in languages()" :key="option.label" :value="option.value" selected>
                        {{ option.label }}
                    </option>
                </Select>

                <InputError v-if="props.form.errors?.language" :message="props.form.errors.language" />
            </div>
        </div>
    </div>

    <!-- Website Name and Subdomain -->
    <div class="grid grid-cols-1 gap-4 md:col-span-3 md:grid-cols-2">
        <div class="col-span-1 flex flex-col gap-2">
            <HeadingSmall
                :title="$t('websiteBuilder.firstStep.business_name_title')"
                titleClass="text-body"
                :description="$t('websiteBuilder.firstStep.business_name_description')"
            />
            <div class="flex flex-col gap-1 ps-2">
                <Input v-model="name" type="text" :placeholder="$t('websiteBuilder.firstStep.business_name_placeholder')" required class="h-10" />
                <InputError v-if="props.form.errors?.name" :message="props.form.errors.name" />
            </div>
        </div>

        <div class="col-span-1 flex flex-col gap-2">
            <HeadingSmall
                :title="$t('websiteBuilder.firstStep.subdomain_title')"
                titleClass="text-body"
                :description="$t('websiteBuilder.firstStep.subdomain_description')"
            />
            <div class="flex flex-col gap-1 ps-2">
                <Input
                    v-model="subdomain"
                    type="text"
                    :placeholder="$t('websiteBuilder.firstStep.subdomain_placeholder')"
                    addon=".spotly.com"
                    class="h-10"
                />
                <InputError v-if="props.form.errors?.subdomain" :message="props.form.errors.subdomain" />
            </div>
        </div>
    </div>

    <!-- About Us Section -->
    <div class="grid grid-cols-1 gap-4 md:col-span-3 md:grid-cols-2">
        <div class="col-span-1 flex flex-col gap-2">
            <HeadingSmall
                :title="$t('websiteBuilder.firstStep.about_us_title_en')"
                titleClass="text-body"
                :description="$t('websiteBuilder.firstStep.about_us_description')"
            />
            <div class="flex flex-col ps-2">
                <Textarea v-model="about_us" :placeholder="$t('websiteBuilder.firstStep.about_us_placeholder')" :maxlength="255" />
                <InputError v-if="props.form.errors?.about_us" :message="props.form.errors.about_us" />
            </div>
        </div>

        <div class="col-span-1 flex flex-col gap-2">
            <HeadingSmall
                :title="$t('websiteBuilder.firstStep.about_us_title_ar')"
                titleClass="text-body"
                :description="$t('websiteBuilder.firstStep.about_us_description')"
            />
            <div class="flex flex-col ps-2">
                <Textarea v-model="about_us_ar" :placeholder="$t('websiteBuilder.firstStep.about_us_placeholder_ar')" :maxlength="255" />
                <InputError v-if="props.form.errors?.about_us_ar" :message="props.form.errors.about_us_ar" />
            </div>
        </div>
    </div>
</template>
