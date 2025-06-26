<script setup lang="ts">
import HeadingSmall from '@/components/headers/HeadingSmall.vue';
import { File, Input, InputError, Select, SelectWithSearch, Textarea } from '@/components/ui/fields';
import { computed } from 'vue';

const props = defineProps<{
    form: {
        website_type: string;
        logo: string;
        name: string;
        subdomain: string;
        description: string;
        language: string;
        errors?: Record<string, string>;
    };
    websiteTypes: Record<string, any>;
}>();

const emit = defineEmits<{
    (e: 'update', field: string, value: string): void;
}>();

const website_type = computed({
    get: () => props.form.website_type,
    set: (val) => emit('update', 'website_type', val),
});

const logo = computed({
    get: () => props.form.logo,
    set: (val) => emit('update', 'logo', val),
});

const name = computed({
    get: () => props.form.name,
    set: (val) => emit('update', 'name', val),
});

const subdomain = computed({
    get: () => props.form.subdomain,
    set: (val) => emit('update', 'subdomain', val),
});

const description = computed({
    get: () => props.form.description,
    set: (val) => emit('update', 'description', val),
});

const language = computed({
    get: () => props.form.language,
    set: (val) => emit('update', 'language', val),
});

const mappedTypes = props.websiteTypes.map((item: any) => ({
    value: item.id,
    label: item.type,
}));
</script>

<template>
    <div key="step1" class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="border-muted col-span-3 w-full border-b pb-3">
            <span class="gradient-text text-2xl font-bold">Business Information</span>
        </div>

        <div class="flex flex-col gap-1 md:col-span-2">
            <div class="flex flex-col gap-2">
                <HeadingSmall title="Website Type" description="Choose the type of your business." />
                <SelectWithSearch v-model="website_type" placeholder="e.g., E-commerce" :options="mappedTypes" />
            </div>
            <InputError v-if="props.form.errors?.website_type" :message="props.form.errors.website_type" />
        </div>

        <div class="flex flex-col gap-1">
            <div class="flex flex-col gap-2">
                <HeadingSmall title="Logo" description="The official logo of your business." />
                <File v-model="logo" type="text" placeholder="e.g., The Corner Cafe" />
            </div>
            <InputError v-if="props.form.errors?.logo" :message="props.form.errors.logo" />
        </div>

        <div class="flex flex-col gap-1">
            <div class="flex flex-col gap-2">
                <HeadingSmall title="Name" description="The official name of your business." />
                <Input v-model="name" type="text" placeholder="e.g., The Corner Cafe" required />
            </div>
            <InputError v-if="props.form.errors?.name" :message="props.form.errors.name" />
        </div>

        <div class="flex flex-col gap-1">
            <div class="flex flex-col gap-2">
                <HeadingSmall title="Subdomain" description="Your unique URL on Spotly." />
                <Input v-model="subdomain" type="text" placeholder="the-corner-cafe" addon=".spotly.com" />
            </div>
            <InputError v-if="props.form.errors?.subdomain" :message="props.form.errors.subdomain" />
        </div>

        <div class="flex flex-col gap-1">
            <div class="flex flex-col gap-2">
                <HeadingSmall title="Language" description="The primary language of your site." />
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

        <div class="flex flex-col md:col-span-3">
            <div class="flex flex-col gap-2">
                <HeadingSmall title="Description" description="A short summary of your business." />
                <Textarea v-model="description" placeholder="e.g., A cozy spot for coffee lovers..." />
            </div>
            <InputError v-if="props.form.errors?.description" :message="props.form.errors.description" />
        </div>
    </div>
</template>
