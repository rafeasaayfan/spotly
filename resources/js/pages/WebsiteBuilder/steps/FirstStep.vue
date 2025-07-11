<script setup lang="ts">
import HeadingSmall from '@/components/headers/HeadingSmall.vue';
import { Input, InputError, Select, SelectWithSearch, Textarea } from '@/components/ui/fields';
import { Store } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    form: {
        website_type: string;
        name: string;
        subdomain: string;
        about_us: string;
        language: string;
        errors?: Record<string, string>;
    };
    websiteTypes: Record<string, any>;
}>();

// Define emit for updating form fields
const emit = defineEmits<{
    (e: 'update', field: string, value: string): void;
}>();

// Computed properties for two-way binding with form fields
const website_type = computed({
    get: () => props.form.website_type,
    set: (val) => emit('update', 'website_type', val),
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

const language = computed({
    get: () => props.form.language,
    set: (val) => emit('update', 'language', val),
});

// Map website types for select options
const mappedTypes = props.websiteTypes.map((item: any) => ({
    value: item.id,
    label: item.type,
}));
</script>

<template>
    <div key="step1" class="grid grid-cols-1 gap-x-6 gap-y-8 md:gap-y-10 md:grid-cols-3">
        <!-- Section Title -->
        <div class="border-muted col-span-1 w-full border-b pb-3 md:col-span-3">
            <h1 class="flex items-center gap-2 text-xl font-bold sm:text-2xl">
                <Store class="text-active-link size-5 sm:size-6" />
                <span class="gradient-text">Business Information</span>
            </h1>
        </div>

        <!-- Website Type Selection -->
        <div class="col-span-1 flex flex-col gap-1 md:col-span-2">
            <div class="flex flex-col gap-2">
                <HeadingSmall title="Website Type" description="Select the category that best describes your business." />
                <SelectWithSearch v-model="website_type" placeholder="e.g., E-commerce" :options="mappedTypes" />
            </div>
            <InputError v-if="props.form.errors?.website_type" :message="props.form.errors.website_type" />
        </div>

        <!-- Language Selection -->
        <div class="col-span-1 flex flex-col gap-1 md:col-span-1">
            <div class="flex flex-col gap-2">
                <HeadingSmall title="Primary Language" description="Choose the main language for your website." />
                <Select v-model="language" placeholder="Select a language...">
                    <option
                        v-for="option in [
                            { value: 'en', label: 'English' },
                            { value: 'ar', label: 'Arabic' },
                            { value: 'fr', label: 'French' },
                        ]"
                        :key="option.label"
                        :value="option.value"
                        selected
                    >
                        {{ option.label }}
                    </option>
                </Select>
            </div>
            <InputError v-if="props.form.errors?.language" :message="props.form.errors.language" />
        </div>

        <!-- Website Name and Subdomain -->
        <div class="grid grid-cols-1 md:grid-cols-2 md:col-span-3 gap-6">
            <div class="col-span-1 flex flex-col gap-1">
                <div class="flex flex-col gap-2">
                    <HeadingSmall title="Business Name" description="Enter the official name of your business or website." />
                    <Input v-model="name" type="text" placeholder="e.g., The Corner Cafe" required />
                </div>
                <InputError v-if="props.form.errors?.name" :message="props.form.errors.name" />
            </div>

            <div class="col-span-1 flex flex-col gap-1">
                <div class="flex flex-col gap-2">
                    <HeadingSmall title="Subdomain" description="Create a unique Spotly URL for your website." />
                    <Input v-model="subdomain" type="text" placeholder="the-corner-cafe" addon=".spotly.com" />
                </div>
                <InputError v-if="props.form.errors?.subdomain" :message="props.form.errors.subdomain" />
            </div>
        </div>

        <!-- About Us Section -->
        <div class="col-span-1 flex flex-col md:col-span-3">
            <div class="flex flex-col gap-2">
                <HeadingSmall title="About Your Business" description="Provide a brief summary describing your business." />
                <Textarea v-model="about_us" placeholder="e.g., A cozy spot for coffee lovers..." />
            </div>
            <InputError v-if="props.form.errors?.about_us" :message="props.form.errors.about_us" />
        </div>
    </div>
</template>
