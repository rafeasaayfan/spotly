<script setup lang="ts">
import HeadingSmall from '@/components/headers/HeadingSmall.vue';
import { Input, InputError, Select, SelectWithSearch, Textarea } from '@/components/ui/fields';
import { computed } from 'vue';

const props = defineProps<{
    form: {
        website_type_id: number;
        name: string;
        subdomain: string;
        about_us: string;
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

const language = computed({
    get: () => props.form.language,
    set: (val) => emit('update', 'language', val),
});
</script>

<template>
    <div class="grid grid-cols-1 gap-4 md:col-span-3 md:grid-cols-2">
        <!-- Website Type Selection -->
        <div class="col-span-1 flex flex-col gap-2">
            <HeadingSmall title="Website Type*" titleClass="text-body" description="Select the category that best describes your business." />
            <div class="flex flex-col gap-1 ps-2">
                <SelectWithSearch v-model="website_type_id" placeholder="e.g., E-commerce" :options="mappedTypes" class="h-10" />
                <InputError v-if="props.form.errors?.website_type_id" :message="props.form.errors.website_type_id" />
            </div>
        </div>

        <!-- Language Selection -->
        <div class="col-span-1 flex flex-col gap-2 md:col-span-1">
            <HeadingSmall title="Primary Language*" titleClass="text-body" description="Choose the main language for your website." />
            <div class="flex flex-col gap-1 ps-2">
                <Select v-model="language" placeholder="Select a language..." class="h-10">
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
                <InputError v-if="props.form.errors?.language" :message="props.form.errors.language" />
            </div>
        </div>
    </div>

    <!-- Website Name and Subdomain -->
    <div class="grid grid-cols-1 gap-4 md:col-span-3 md:grid-cols-2">
        <div class="col-span-1 flex flex-col gap-2">
            <HeadingSmall title="Business Name*" titleClass="text-body" description="Enter the official name of your business or website." />
            <div class="flex flex-col gap-1 ps-2">
                <Input v-model="name" type="text" placeholder="e.g., The Corner Cafe" required class="h-10" />
                <InputError v-if="props.form.errors?.name" :message="props.form.errors.name" />
            </div>
        </div>

        <div class="col-span-1 flex flex-col gap-2">
            <HeadingSmall title="Subdomain*" titleClass="text-body" description="Create a unique Spotly URL for your website." />
            <div class="flex flex-col gap-1 ps-2">
                <Input v-model="subdomain" type="text" placeholder="the-corner-cafe" addon=".spotly.com" class="h-10" />
                <InputError v-if="props.form.errors?.subdomain" :message="props.form.errors.subdomain" />
            </div>
        </div>
    </div>

    <!-- About Us Section -->
    <div class="col-span-1 flex flex-col gap-2 md:col-span-3">
        <HeadingSmall title="About Your Business*" titleClass="text-body" description="Provide a brief summary describing your business." />
        <div class="flex flex-col ps-2">
            <Textarea v-model="about_us" placeholder="e.g., A cozy spot for coffee lovers..." :maxlength="255" />
            <InputError v-if="props.form.errors?.about_us" :message="props.form.errors.about_us" />
        </div>
    </div>
</template>
