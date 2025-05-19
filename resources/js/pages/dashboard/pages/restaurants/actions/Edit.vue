<script setup lang="ts">
import { PrimaryButton } from '@/components/ui/buttons';
import { Input, Select, File } from '@/components/ui/fields';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowBigLeft } from 'lucide-vue-next';

const props = defineProps<{
    data: Record<string, any>;
    users: Array<{ id: number; name: string }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Restaurants',
        href: '/dashboard/restaurants',
    },
];

const form = useForm({ ...props.data });

function submit() {
    form.put(route('dashboard.restaurants.update', props.data.id));
}

const columns = {
    name: {
        type: 'text',
        label: 'Name',
    },
    user_id: {
        type: 'select',
        label: 'Owner',
    },
    image: {
        type: 'image',
        label: 'Image',
    },
    address: {
        type: 'text',
        label: 'Address',
    },
    city: {
        type: 'text',
        label: 'City',
    },
    country: {
        type: 'text',
        label: 'Country',
    },
    phone: {
        type: 'text',
        label: 'Phone',
    },
    email: {
        type: 'email',
        label: 'Email',
    },
    website: {
        type: 'text',
        label: 'Webiste url',
    },
    opening_hours: {
        type: 'time',
        label: 'Opening hours',
    },
    closing_hours: {
        type: 'time',
        label: 'Closing hours',
    },
    description: {
        type: 'textarea',
        label: 'Description',
    },
};
</script>

<template>
    <Head title="Restaurants" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div class="w-full p-4">
            <Link :href="route('dashboard.restaurants.index')" class="flex cursor-pointer items-center gap-3">
                <ArrowBigLeft class="size-4" />
                <p class="text-semibold">Go Back</p>
            </Link>
        </div>

        <div class="m-4 flex flex-col gap-3 rounded-md border bg-zinc-950 px-8 py-4 shadow-md">
            <h3>Edit restaurant data</h3>

            <form @submit.prevent="submit" class="grid gap-4 lg:grid-cols-2 xl:grid-cols-3">
                <div v-for="(config, field) in columns" :key="field">
                    <label class="mb-1 block font-medium text-white">{{ config.label }}</label>

                    <!-- Text / Email -->
                    <template v-if="config.type === 'text' || config.type === 'email'">
                        <Input :type="config.type" v-model="form[field]" class="input" />
                    </template>

                    <!-- Textarea -->
                    <template v-else-if="config.type === 'textarea'">
                        <textarea class="input w-full rounded-md border bg-zinc-800 p-2 text-white" />
                    </template>

                    <!-- Select -->
                    <template v-else-if="config.type === 'select'">
                        <Select class="input">
                            <option v-for="option in props.users" :key="option.id ?? option" :value="option.id ?? option">
                                {{ option.name ?? option }}
                            </option>
                        </Select>
                    </template>

                    <!-- Image -->
                    <template v-else-if="config.type === 'image'">
                        <File
                            :src="form[field] ? form[field] : '/images/default-image.avif'"
                            @change="
                                (e: Event) => {
                                    const target = e.target as HTMLInputElement;
                                    form[field] = target?.files?.[0] ?? null;
                                }
                            "
                        />
                    </template>

                    <div v-if="form.errors[field]" class="mt-1 text-sm text-red-500">
                        {{ form.errors[field] }}
                    </div>
                </div>

                <div class="flex justify-end lg:col-span-2 xl:col-span-3">
                    <PrimaryButton :disabled="form.processing">Update</PrimaryButton>
                </div>
            </form>
        </div>
    </DashboardLayout>
</template>
