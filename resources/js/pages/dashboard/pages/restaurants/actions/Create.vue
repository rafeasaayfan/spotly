<script setup lang="ts">
import { PrimaryButton } from '@/components/ui/buttons';
import { File, Input, Select } from '@/components/ui/fields';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowBigLeft } from 'lucide-vue-next';

const props = defineProps<{
    users: Array<{ id: number; name: string }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Restaurants',
        href: '/dashboard/restaurants',
    },
];

function submit() {
    form.post(route(`dashboard.restaurants.store`));
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

const form = useForm<Record<string, string | File | null>>(Object.fromEntries(Object.keys(columns).map((key) => [key, ''])));
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
            <h3>Create new restaurants</h3>

            <form class="grid gap-4 lg:grid-cols-2 xl:grid-cols-3" @submit.prevent="submit">
                <div v-for="(config, field) in columns" :key="field">
                    <label class="mb-1 block font-medium">{{ config.label }}</label>

                    <template v-if="config.type === 'text' || config.type === 'email' || config.type === 'password'">
                        <Input :type="config.type" v-model="form[field]" class="input" />
                    </template>

                    <template v-else-if="config.type === 'textarea'">
                        <textarea />
                    </template>

                    <template v-else-if="config.type === 'select'">
                        <Select v-model="form[field]">
                            <option v-for="option in props.users" :key="option.id ?? option" :value="option.id ?? option">
                                {{ option.name ?? option }}
                            </option>
                        </Select>
                    </template>

                    <template v-else-if="config.type === 'image'">
                        <File
                            @change="
                                (e: Event) => {
                                    const target = e.target as HTMLInputElement;
                                    form[field] = target?.files?.[0] ?? null;
                                }
                            "
                        />
                    </template>

                    <template v-else-if="config.type === 'time'">
                        <Input type="time" v-model="form[field]" />
                    </template>

                    <div v-if="form.errors[field]" class="mt-1 text-sm text-red-500">
                        {{ form.errors[field] }}
                    </div>
                </div>

                <div class="flex justify-end lg:col-span-2 xl:col-span-3">
                    <PrimaryButton :disabled="form.processing">Submit</PrimaryButton>
                </div>
            </form>
        </div>
    </DashboardLayout>
</template>
